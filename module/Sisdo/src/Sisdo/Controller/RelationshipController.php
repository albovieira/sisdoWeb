<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Sisdo\Controller;

use Application\Constants\MensagemConst;
use Application\Constants\UsuarioConst;
use Application\Custom\ActionControllerAbstract;
use Sisdo\Constants\RelationshipConst;
use Sisdo\Entity\TemplateEmail;
use Sisdo\Filter\TemplateEmailFilter;
use Sisdo\Form\TemplateEmailForm;
use Sisdo\Service\RelationshipService;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class RelationshipController extends ActionControllerAbstract
{
    public function indexAction()
    {

        /** @var RelationshipService $service */
        $service = $this->getFromServiceLocator(RelationshipConst::SERVICE);
        $grid = $service->getGrid();
        return new ViewModel(
            array(
                'grid' => $grid,
                'botoesHelper' => $this->getBotoesHelper()
            )
        );
    }

    public function getDadosAction(){

        /** @var RelationshipService $service */
        $service = $this->getFromServiceLocator(RelationshipConst::SERVICE);
        $grid = $service->getGridDados();

        return new JsonModel($grid);
    }

    public function enviarEmailAction(){
        /** @var RelationshipService $service */
        $service = $this->getFromServiceLocator(RelationshipConst::SERVICE);

        $form = new TemplateEmailForm(null, null ,true, $service);

        $template = $this->getRequest()->getQuery('template');

        if ($template) {

            if ($service->enviaEmail($template)) {
                return new JsonModel(
                    array('retorno' => 'sucesso')
                );
            } else {
                return new JsonModel(
                    array('retorno' => 'erro')
                );
            }
        }

        $view = new ViewModel();
        $view->setVariables(
            array(
                'form' => $form,
            )
        );
        $view->setTerminal(true);
        return $view;

    }

    public function incluirModeloAction()
    {
        //var_dump($this->getRequest()->getPost());die;
        /** @var RelationshipService $service */
        $service = $this->getFromServiceLocator(RelationshipConst::SERVICE);

        /** @var \Application\Entity\User $userLogado */
        $userLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();
        $form = new TemplateEmailForm(null,$userLogado,false,$service);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setInputFilter(new TemplateEmailFilter());
            $form->setData($post);

            if ($form->isValid()) {
                try {
                    /** @var TemplateEmail $template */
                    $template = $form->getData();
                    $template->setInstitutionUser($userLogado);

                    if ($service->salvarModeloEmail($template)) {
                        $this->flashMessenger()->addSuccessMessage(MensagemConst::CADASTRO_SUCESSO);
                        return $this->redirect()->toRoute('relacionamento');
                    } else {
                        $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                }
            }
        }

        $view = new ViewModel();
        $view->setVariable('form',$form);
        $view->setTemplate('sisdo/relationship/formulario-modelo-email');

        return $view;
    }

    public function editarModeloAction()
    {
        //var_dump($this->getRequest()->getPost());die;
        /** @var RelationshipService $service */
        $service = $this->getFromServiceLocator(RelationshipConst::SERVICE);

        $id = $this->params()->fromRoute('id');
        $template = $service->getTemplateById($id);
        /** @var \Application\Entity\User $userLogado */
        $userLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();
        $form = new TemplateEmailForm(null,$userLogado,false,$service);
        $form->bind($template);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setInputFilter(new TemplateEmailFilter());
            $form->setData($post);

            if ($form->isValid()) {
                try {
                    /** @var TemplateEmail $template */
                    $template = $form->getData();
                    $template->setInstitutionUser($userLogado);

                    if ($service->salvarModeloEmail($template)) {
                        $this->flashMessenger()->addSuccessMessage(MensagemConst::OPERACAO_SUCESSO);
                        return $this->redirect()->toRoute('relacionamento',array('action' => 'ver-modelo'));
                    } else {
                        $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                }
            }
        }

        $view = new ViewModel();
        $view->setVariable('form',$form);
        $view->setTemplate('sisdo/relationship/formulario-modelo-email');
        return $view;
    }

    public function verModeloAction(){
        /** @var RelationshipService $service */
        $service = $this->getFromServiceLocator(RelationshipConst::SERVICE);
        $grid = $service->getGridTemplate();
        return new ViewModel(
            array(
                'grid' => $grid,
                'botoesHelper' => $this->getBotoesHelper()
            )
        );
    }

    public function getGridTemplateAction(){

        /** @var RelationshipService $service */
        $service = $this->getFromServiceLocator(RelationshipConst::SERVICE);
        $grid = $service->getGridDadosTemplate();

        return new JsonModel($grid);
    }

    public function getBotoesHelper()
    {
        return array(
            $this->addBotaoHelper('btn-incluir btn-primary btn btn-xs', 'glyphicon glyphicon-envelope',
                'Enviar Email', '', '#modal_envia_email', true, array('data-toggle' => "modal", 'onclick' => '$("#modal_envia_email .modal-body").load("/relacionamento/enviar-email")')),

            $this->addBotaoHelper('btn-incluir btn-success btn btn-xs', 'glyphicon glyphicon-plus', 'Incluir Template Email','',
                $this->url()->fromRoute('relacionamento', array('action' => 'incluir-modelo'))),

            $this->addBotaoHelper('btn-incluir btn-warning btn btn-xs', 'glyphicon glyphicon-eye-open', 'Ver Templates','',
                $this->url()->fromRoute('relacionamento', array('action' => 'ver-modelo'))),
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
