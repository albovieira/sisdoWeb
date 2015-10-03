<?php

namespace Application\Controller;

use Application\Constants\RotasConst as Rotas;
use Application\Constants\UsuarioConst;
use Application\Dao\UsuarioDao;
use Application\Entity\Usuario;
use Application\Filter\AlterarSenhaFilter;
use Application\Filter\LoginFilter;
use Application\Filter\ReiniciarSenhaFilter;
use Application\Filter\ValidarChaveAcessoFilter;
use Application\Form\AlterarSenhaForm;
use Application\Form\LoginForm;
use Application\Form\ReiniciarSenhaForm;
use Application\Form\ValidarChaveAcessoForm;
use Application\Util\Mensagens;
use Zend\Authentication\Result;
use Zend\Form\Form;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;
use ZfcUser\Controller\UserController;

/**
 * Class AutenticacaoController.
 */
class AutenticacaoController extends UserController
{

    /**
     * @var string
     */
    private $resetPassword;
    /**
     * @var Form
     */
    private $validaAcesso;

    /**
     * Renderiza a tela de login caso o usuario nao esteja logado senao redireciona para tela principal
     *
     * @return mixed|\Zend\Http\Response|ViewModel
     */
    public function loginAction()
    {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        //$this->layout('layout/unauthorized');

        /** @var Request $request */
        $request = $this->getRequest();
        $form = $this->getFormularioLogin();
        $formForgPwd = $this->getFormularioReiniciarSenha();

        $resetPwd = '';
        if (($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) ||
            ($request->getQuery()->get('reiniciarSenha'))
        ) {
            $redirect = $request->getQuery()->get('redirect');
            $resetPwd = $request->getQuery()->get('reiniciarSenha', false);
        } else {
            $redirect = false;
        }

        $msgs = array();
        if ($this->flashMessenger()->hasMessages('SUCESSO')) {
            $msg = $this->flashMessenger()->getMessages('SUCESSO');
            $msgs['sucesso'] = $msg[0];
        }
        if ($this->flashMessenger()->hasMessages('LOGIN')) {
            $msg = '';
            foreach ($this->flashMessenger()->getMessages('LOGIN') as $m) {
                $msg .= "<li>{$m}</li>\n";
            }
            $msgs['login'] = $msg;
        }
        if ($this->flashMessenger()->hasMessages('RESET')) {
            $msg = '';
            foreach ($this->flashMessenger()->getMessages('RESET') as $m) {
                $msg .= "<li>{$m}</li>\n";
            }
            $msgs['reset'] = $msg;
        }
        if ($this->flashMessenger()->hasMessages('ALTERAR_SENHA')) {
            $msg = '';
            foreach ($this->flashMessenger()->getMessages('ALTERAR_SENHA') as $m) {
                $msg .= "{$m}\n";
            }
            $msgs['alterar_senha'] = $msg;
        }

        $view = new ViewModel();
        $view->setVariable('form', $form);
        $view->setVariable('formForgPwd', $formForgPwd);
        $view->setVariable('mensagens', $msgs);
        $view->setVariable('redirect', $redirect);
        $view->setVariable('resetPwd', $resetPwd);

        if (!$request->isPost()) {
            return $view;
        }

        if ($request->getPost('form') == $formForgPwd->getName()) {
            $formForgPwd->setData($request->getPost());

            if (!$formForgPwd->isValid()) {
                $view->setVariable('resetPwd', 'reiniciarSenha=1');
                $view->setVariable('formForgPwd', $formForgPwd);

                return $view;
            }

            $service = $this->getUserService();
            //$email = $formForgPwd->get(UsuarioConst::FLD_EMAIL);
            //if (!$service->esqueciSenha($email->getValue())) {
             //   $resetPwd = 'reiniciarSenha=1';
             //   $this->flashMessenger()->addMessage(Mensagens::getMensagem('M05_1'), 'RESET');
            //} else {
            //    $this->flashMessenger()->addMessage(Mensagens::getMensagem('M08'), 'SUCESSO');
           /// }

            return $this->redirect()->toUrl($this->url()->fromRoute(Rotas::LOGIN) . ($request || $resetPwd ? '?' : '') . ($resetPwd ? $resetPwd . '&' : '') . ($redirect ? 'redirect=' . rawurlencode($redirect) : ''));
        } else {
            $form->setData($request->getPost());

            if (!$form->isValid()) {
                $view->setVariable('form', $form);

                return $view;
            }
        }

        // clear adapters
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

        return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));

    }



    /**
     * Ação responsavel por validar o login do usuário
     *
     * @return \Zend\Http\Response
     */
    public function authenticateAction()
    {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));


        $result = $adapter->prepareForAuthentication($this->getRequest());

        // Return early if an adapter returned a response
        if ($result instanceof Response) {
            return $result;
        }

        /** @var \Zend\Authentication\Result $auth */
        $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);

        if (!$auth->isValid()) {
            foreach ($auth->getMessages() as $message) {
                $this->flashMessenger()->addMessage($message, 'LOGIN');
            }

            $pros = $this->processaErroAutenticacao($auth);

            if ($pros instanceof Response) {
                return $pros;
            } else {
                $adapter->resetAdapters();

                return $this->redirect()->toUrl(
                    $this->url()->fromRoute(static::ROUTE_LOGIN) .
                    ($redirect ? '?redirect=' . rawurlencode($redirect) : '')
                );
            }
        }

        $pros = $this->processaErroAutenticacao($auth);

        if ($pros instanceof Response) {
            foreach ($auth->getMessages() as $message) {
                $this->flashMessenger()->addMessage($message, 'LOGIN');
            }

            return $pros;
        } else {
            if ($redirect) {
                return $this->redirect()->toUrl($redirect);
            } else {
                return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
            }
        }
    }

    /**
     * Trata o erro ocorrido na autenticação do usuário
     *
     * @param $auth
     * @return bool|\Zend\Http\Response
     */
    private function processaErroAutenticacao($auth)
    {
        return true;
        switch ($auth->getCode()) {

            case UsuarioConst::ERRO_SENHA_EXPIRADA:
                return $this->redirect()->toUrl($this->url()->fromRoute(Rotas::ALTERAR_SENHA));
                break;

            case UsuarioConst::ERRO_PRIMEIRO_ACESSO:
                $this->flashMessenger()->addMessage($auth, 'loginAuth');

                return $this->redirect()->toUrl($this->url()->fromRoute(Rotas::VALIDAR_CHAVE_ACESSO));
                break;
            case UsuarioConst::ERRO_USUARIO_INATIVO:
                /** @var Usuario $user */
                $user = $auth->getIdentity();

                /** @var UsuarioDao $usuarioDao */
                $usuarioDao = $this->getServiceLocator()->get('UsuarioDao');
                /** @var Usuario $gestor */
                $gestor = $usuarioDao->getGestorUnidade($user->getUnidadeOrganizacional());

                if (!$gestor) {
                    return false;
                }

                $email = new EmailUtil($this->getServiceLocator());
                $email->setSender(
                    UsuarioSemPerfilAssociadoEmail::getNomeRemetente(),
                    UsuarioSemPerfilAssociadoEmail::getRemetente()
                );
                $email->sendMail(
                    $gestor->getNomUsuario(),
                    $gestor->getEmail(),
                    UsuarioSemPerfilAssociadoEmail::getAssunto(),
                    UsuarioSemPerfilAssociadoEmail::getEmail(array('nome' => $user->getNomUsuario(), 'login' => $user->getUsername()))
                );

                return true;
                break;
            default:
                break;
        }
    }


    /**
     * Register um novo usuario
     */
    public function registerAction()
    {
        return $this->forward()->dispatch('Application\Controller\Usuario', array('action' => 'incluir'));
    }


    /**
     * Redireciona para alterarSenhaAction
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function changepasswordAction()
    {
        return $this->alterarSenhaAction();
    }

    /**
     * Altera senha do usuario
     *
     * @return array|\Zend\Http\Response|ViewModel
     */
    public function alterarSenhaAction()
    {
        $userKey = $this->getRequest()->getQuery()->get('userkey', null);
        $hadIdentity = true;
        if ($userKey) {
            $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
            $result = $adapter->prepareForAuthentication($this->getRequest());
            // Return early if an adapter returned a response
            if ($result instanceof Response) {
                return $result;
            }

            $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);

            if (!$auth->isValid()) {
                $this->flashMessenger()->addMessage(Mensagens::getMensagem('ALTERAR_SENHA_INVALIDA'), 'ALTERAR_SENHA', 2);
            }

            // if the user isn't logged in, we can't change password
            if (!$this->zfcUserAuthentication()->hasIdentity()) {
                // redirect to the login redirect route
                return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
            }

            $hadIdentity = false;

        }

        if ($this->zfcUserAuthentication()->hasIdentity()) {

            $identity = $this->zfcUserAuthentication()->getIdentity();

            $this->layout('layout/unauthorized');

            $form = $this->getAlterarSenhaForm();
            $prg = $this->prg(Rotas::ALTERAR_SENHA);

            $fm = $this->flashMessenger()->setNamespace('change-password')->getMessages();
            if (isset($fm[0])) {
                $status = $fm[0];
            } else {
                $status = null;
            }

            $view = new ViewModel();
            $view->setTemplate('alterar-senha');
            $view->setVariable('returnUrl', $this->getOptions()->getLoginRedirectRoute());
            $view->setVariable('userKey', $userKey);
            $view->setVariable('username', $identity->getUsername());
            $view->setVariable('identity', $hadIdentity);


            $form->get('identity')->setValue($identity->getUsername());

            if ($prg instanceof Response) {
                return $prg;
            } elseif ($prg === false) {
                $view->setVariables(array(
                    'status' => $status,
                    'changePasswordForm' => $form,
                ));

                if (!$hadIdentity) {
                    $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
                    $this->zfcUserAuthentication()->getAuthAdapter()->logoutAdapters();
                    $this->zfcUserAuthentication()->getAuthService()->clearIdentity();
                }

                return $view;
            }

            $form->setData($prg);

            if (!$form->isValid()) {
                $view->setVariables(array(
                    'status' => false,
                    'changePasswordForm' => $form,
                ));


                if (!$hadIdentity) {
                    $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
                    $this->zfcUserAuthentication()->getAuthAdapter()->logoutAdapters();
                    $this->zfcUserAuthentication()->getAuthService()->clearIdentity();
                }

                return $view;
            }

            $data = $form->getData();

            if ($hadIdentity) {
                $isEqual = strcmp($data['newCredential'], $data['credential']) == 0 ? true : false;
            } else {
                $isEqual = false;
            }

            if (!((!$isEqual) && ($this->getUserService()->changePassword($form->getData())))) {
                $view->setVariables(array(
                    'status' => false,
                    'message' => Mensagens::getMensagem($isEqual ? 'M13' : 'M05_2'),
                    'changePasswordForm' => $form,
                ));


                if (!$hadIdentity) {
                    $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
                    $this->zfcUserAuthentication()->getAuthAdapter()->logoutAdapters();
                    $this->zfcUserAuthentication()->getAuthService()->clearIdentity();
                }

                return $view;
            }

            $this->flashMessenger()->addSuccessMessage(Mensagens::getMensagem('M09'));

            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        } else {
            return $this->redirect()->toRoute($this->getOptions()->getLogoutRedirectRoute());
        }
    }

    /**
     * Valida chave de acesso na alteração da senha
     *
     * @return mixed|ViewModel
     */
    public function validarChaveAcessoAction()
    {

        /** @var Result $response */
        $response = null;
        if (!$this->getRequest()->isPost()) {
            if (!$this->flashMessenger()->hasMessages('loginAuth')) {
                // redirect to the login redirect route
                return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
            } else {
                $response = $this->flashMessenger()->getMessages('loginAuth');
                $response = $response[0];
            }
            $msg = $response->getMessages();
        }

        if (isset($msg[0])) {
            $status = array('type' => 'warning', 'message' => $msg[0]);
        } else {
            $status = null;
        }

        $view = new ViewModel();
        $view->setTemplate('validar-chave-acesso');

        $this->layout('layout/unauthorized');

        $form = $this->getValidaAcessoForm();

        if (!$this->getRequest()->isPost()) {
            $identify = $response->getIdentity();
            $id = $form->get(UsuarioConst::FLD_IDENTITY);
            $id->setValue($identify['i']);
            $pwd = $form->get(UsuarioConst::FLD_SENHA);
            $pwd->setValue($identify['p']);

            $view->setVariables(array(
                'status' => $status,
                'form' => $form,
            ));

            return $view;
        }

        $form->setData($this->getRequest()->getPost());

        if (!$form->isValid()) {
            $view->setVariables(array(
                'status' => array(),
                'form' => $form,
            ));

            return $view;
        }

        $valid = $this->getUserService()->validaAcesso($form->getData());
        if ($valid < Result::FAILURE) {
            $status = array();
            if ($valid == UsuarioConst::ERRO_CHAVE_ACESSO_INVALIDO) {
                $status = array('type' => 'error', 'message' => Mensagens::getMensagem('M16'));
            }
            if ($valid == UsuarioConst::ERRO_EXCEDIDO_LIMITE_CHAVE_ACESSO) {
                $status = array('type' => 'error', 'message' => Mensagens::getMensagem('M23'));
            }

            $view->setVariables(array(
                'status' => $status,
                'form' => $form,
            ));

            return $view;
        }

        // clear adapters
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

        return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
    }

    /**
     * Retorna form para alteração de senha
     *
     * @return \Zend\Form\Form
     */
    public function getAlterarSenhaForm()
    {
        if (!$this->changePasswordForm) {
            /** @var  $options */
            $options = $this->getServiceLocator()->get('zfcuser_module_options');
            $form = new AlterarSenhaForm(null, $options);
            $form->setInputFilter(new AlterarSenhaFilter($options));
            $this->setChangePasswordForm($form);
        }

        return $this->changePasswordForm;
    }

    /**
     * Retorna form para reiniciar senha
     *
     * @return LoginForm
     */
    private function getFormularioReiniciarSenha()
    {
        if (!$this->resetPassword) {
            $options = $this->getServiceLocator()->get('zfcuser_module_options');

            $form = new ReiniciarSenhaForm('reiniciarSenhaForm', $options);
            $form->setInputFilter(new ReiniciarSenhaFilter($options));
            $this->setResetPasswordForm($form);
        }

        return $this->resetPassword;
    }

    /**
     * Retorna o formulario de login
     *
     * @return LoginForm
     */
    private function getFormularioLogin()
    {
        if (!$this->loginForm) {
            $options = $this->getServiceLocator()->get('zfcuser_module_options');

            $form = new LoginForm('loginForm', $options);
            $form->setInputFilter(new LoginFilter($options));
            $this->setLoginForm($form);
        }

        return $this->loginForm;
    }

    /**
     * Retorna form que valida o acesso
     *
     * @return LoginForm
     */
    private function getValidaAcessoForm()
    {
        if (!$this->validaAcesso) {
            $options = $this->getServiceLocator()->get('zfcuser_module_options');

            $form = new ValidarChaveAcessoForm('reiniciarSenhaForm', $options);
            $form->setInputFilter(new ValidarChaveAcessoFilter($options));
            $this->setValidaAcessoForm($form);
        }

        return $this->validaAcesso;
    }

    /**
     * Altera formulario referente a troca de senha
     *
     * @param Form $form
     *
     * @return $this
     */
    private function setResetPasswordForm(Form $form)
    {
        $this->resetPassword = $form;

        return $this;
    }

    /**
     * Altera formulario de login
     *
     * @param Form $loginForm
     *
     * @return $this
     */
    public function setLoginForm(Form $loginForm)
    {
        $this->loginForm = $loginForm;

        return $this;
    }

    /**
     * Altera formulario de validação de acesso
     *
     * @param Form $form
     *
     * @return $this
     */
    private function setValidaAcessoForm(Form $form)
    {
        $this->validaAcesso = $form;

        return $this;
    }
}
