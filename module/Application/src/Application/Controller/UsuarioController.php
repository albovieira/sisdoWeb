<?php

namespace Application\Controller;

use Application\Constants\AccessControlConst as Acl;
use Application\Constants\MensagensConst;
use Application\Constants\RotasConst as Rotas;
use Application\Constants\RotasConst;
use Application\Constants\UsuarioConst as Usuario;
use Application\Custom\ActionControllerAbstract;
use Application\Form\UsuarioForm;
use Application\Service\UsuarioService;
use Application\Util\Mensagens;
use Zend\Crypt\Password\Bcrypt;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class ModeloController.
 */
class UsuarioController extends ActionControllerAbstract
{

    const ROUTE_LOGIN = 'login';
    const ROUTE_REGISTER = 'registrar';
    const CONTROLLER_NAME = 'zfcuser';
    const USUARIO_FORM = 'user_edit_form';

    /**
     * Construtor da Classe Controladora
     */
    public function __construct()
    {
        $this->module = 'Administrativo';
        $this->title = 'Usuário';
        $this->subTitle = '';
    }

    /**
     * Renderiza a interface principal do grid
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        /** @var UsuarioService $usuarioService */
        $usuarioService = $this->getFromServiceLocator(Usuario::SERVICE);

        $jqGridTable = $usuarioService->getGrid();
        return new ViewModel(array(
            'jqGridTable' => $jqGridTable,
            'botoesListagem' => $this->getBotoesListagem(),
        ));
    }

    /**
     * Retorna o grid com os alvaras que podem ser expedidos
     *
     * @return JsonModel
     */
    public function filtroAction()
    {
        /** @var UsuarioService $usuarioService */
        $usuarioService = $this->getFromServiceLocator(Usuario::SERVICE);
        $grid = $usuarioService->getGridFiltro();

        return new JsonModel($grid);
    }

    /**
     * Renderiza formulario para inclusao de novo usuario
     *
     * @return ViewModel
     */
    public function incluirAction()
    {
        $request = $this->getRequest();

        /** @var UsuarioService $service */
        $service = $this->getFromServiceLocator(Usuario::SERVICE);

        /** @var  UsuarioForm $form */
        $form = $this->getFromServiceLocator(Usuario::ZFCUSER_REGISTER_FORM);

        $view = new ViewModel();
        $view->setTemplate('application/usuario/incluir');
        $this->layout()->setVariable('title', 'Cadastrar Usuário');

        if ($service->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }

        $redirectUrl = $this->url()->fromRoute(self::ROUTE_REGISTER) . ($redirect ? '?redirect=' . rawurlencode($redirect) : '');
        $prg = $this->prg($redirectUrl, true);

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {

            return $view->setVariables(array(
                'registerForm' => $form,
                'enableRegistration' => true,
                'redirect' => $redirect,
            ));
        }

        $post = $prg;
        $form->setData($post);

        if (!$form->isValid()) {

            return $view->setVariables(array(
                'registerForm' => $form,
                'enableRegistration' => true,
                'redirect' => $redirect,
            ));
        }

        try {
            $user = $service->incluir($post);

            if (!$user) {
                $redirect = isset($prg['redirect']) ? $prg['redirect'] : null;

                $this->flashMessenger()->addErrorMessage('Erro');
                return $view->setVariables(array(
                    'registerForm' => $form,
                    'enableRegistration' => true,
                    'redirect' => $redirect,
                ));
            }else{
                $this->flashMessenger()->addSuccessMessage('Cadastrado com sucesso');
            }
        } catch (\Exception $e) {

            $this->flashMessenger()->addErrorMessage('ERROOO');
            return $view->setVariables(array(
                'registerForm' => $form,
                'enableRegistration' => true,
                'redirect' => $redirect,
            ));
        }

        return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect=' . rawurlencode($redirect) : ''));
    }

    /**
     * Editar usuario
     *
     * @return ViewModel
     */
    public function editarAction()
    {
        $login = $this->params()->fromRoute('id', null);

        /** @var UsuarioService $service */
        $service = $this->getFromServiceLocator(Usuario::SERVICE);
        /** @var \Application\Entity\User $usuario */
        $usuario = $service->findByLogin($login);
        if (!$usuario) {
            return $this->redirect()->toRoute(Rotas::USUARIO);
        }

        /** @var \Application\Entity\User $usuarioLogado */
        $usuarioLogado = $this->getFromServiceLocator(Usuario::ZFCUSER_AUTH_SERVICE)->getIdentity();

        /** @var  UsuarioForm $form */
        $form = $this->getFromServiceLocator(self::USUARIO_FORM);
        $form->get(Usuario::FLD_ORGAO)->setValue($usuario->getUnidade()->getOrgao()->getSeqOrgao());

        $redirectUrl = $this->url()->fromRoute(Rotas::USUARIO) . "/editar/{$login}";
        $prg = $this->prg($redirectUrl, true);

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            $form->bind($usuario);
            $form->setObject($usuario);

            $unidadeAtual = $form->get(Usuario::FLD_UNIDADE)->getValue()->getSeqUnidade();
            $unidades = $this->createSelectUnidades($form->get(Usuario::FLD_ORGAO)->getValue());
            $form->get(Usuario::FLD_UNIDADE)->setValueOptions($unidades);
            $form->get(Usuario::FLD_UNIDADE)->setValue($unidadeAtual);

            return array(
                'form' => $form,
                'isCorregedor' => $usuarioLogado->isCorregedorGeral()
            );
        }


        $form->setData($prg);

        if (strcmp($usuario->getLoginUsuario(), $prg[Usuario::FLD_LOGIN]) === 0) {
            $form->getInputFilter()->remove(Usuario::FLD_LOGIN);
        }

        $isValid = true;
        if ($form->get(Usuario::FLD_SENHA)->getValue()) {
            $pwd = $form->get(Usuario::FLD_SENHA)->getValue();
            $bcrypt = new Bcrypt();
            $bcrypt->setCost($service->getOptions()->getPasswordCost());

            if ($bcrypt->verify($pwd, $usuario->getPassword())) {
                $form->setMessages(array(Usuario::FLD_SENHA => [Mensagens::getMensagem('M13')]));
                $isValid = false;
            }
        }

        if ($isValid && $form->isValid()) {
            try {
                if ($usuario = $service->editar($prg)) {
                    $this->flashMessenger()->addSuccessMessage(Mensagens::getMensagem('M09'));
                    return $this->redirect()->toRoute(Rotas::USUARIO);

                } else {
                    $this->flashMessenger()->addErrorMessage(Mensagens::getMensagem('MERRO'));
                }
            } catch (\Exception $e) {
                $escaper = new \Zend\Escaper\Escaper('utf-8');
                $msg = '<br/><br/>' . $escaper->escapeJs(nl2br($e->getMessage()));
                $this->flashMessenger()->addErrorMessage(Mensagens::getMensagem('MERRO') . $msg);
            }

        }

        if($usuario){
            $unidadeAtual = $usuario->getUnidade()->getSeqUnidade();
            $unidades = $this->createSelectUnidades($form->get(Usuario::FLD_ORGAO)->getValue());
            $form->get(Usuario::FLD_UNIDADE)->setValueOptions($unidades);
            $form->get(Usuario::FLD_UNIDADE)->setValue($unidadeAtual);
        }
        return array(
            'form' => $form,
            'isCorregedor' => $usuarioLogado->isCorregedorGeral()
        );

    }




    /**
     * Excluir usuario
     *
     * @return ViewModel
     */
    public function excluirAction()
    {

        $login = $this->params()->fromRoute('id', null);

        /** @var UsuarioService $service */
        $service = $this->getFromServiceLocator(Usuario::SERVICE);
        $usuario = $service->findByLogin($login);

        if (!$usuario) {
            return $this->redirect()->toRoute(Rotas::USUARIO);
        }

        try {
            if ($service->excluir($usuario)) {
                $this->flashMessenger()->addSuccessMessage('sucesso');
            } else {
                $this->flashMessenger()->addErrorMessage('errooo');
            }
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage('errooo');
        }

        return $this->redirect()->toRoute(Rotas::USUARIO);

    }


    /**
     * Retorna botoes de acao para interface.
     *
     * @return array
     */
    public function getBotoesListagem()
    {
        return array(
            $this->addBotaoHelper('btn-incluir-unidade btn-success', 'fa-file-text', '', 'Incluir',
                $this->url()->fromRoute(RotasConst::USUARIO, array('action' => 'incluir')), true),

        );
    }

    /**
     * Retorna o titulo da pagina (especializar)
     *
     * @return mixed
     */
    public function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    /**
     * @return mixed
     */
    public function getBreadcrumb()
    {
        // TODO: Implement getBreadcrumb() method.
    }

}
