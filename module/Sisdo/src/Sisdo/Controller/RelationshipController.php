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
use Application\Custom\ActionControllerAbstract;
use Sisdo\Constants\RelationshipConst;
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

        $form = new TemplateEmailForm(null, null, $service);

        $view = new ViewModel();
        $view->setVariables(
            array(
                'form' => $form,
            )
        );
        $view->setTerminal(true);
        return $view;


        //$service->enviaEmail();
    }

    public function incluirModeloAction()
    {
        /** @var RelationshipService $service */
        $service = $this->getFromServiceLocator(RelationshipConst::SERVICE);

        $form = new TemplateEmailForm($service);
        //$filter = new ProdutoFilter();

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            //$form->setInputFilter($filter);
            $form->setData($post);

            if ($form->isValid()) {
                try {
                    if ($service->incluirModeloEmail($form->getData())) {
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

    }

    public function getBotoesHelper()
    {
        return array(
            $this->addBotaoHelper('btn-incluir btn-primary btn btn-xs', 'glyphicon glyphicon-envelope',
                'Enviar Email', '', '#modal_envia_email', true, array('data-toggle' => "modal", 'onclick' => '$("#modal_envia_email .modal-body").load("/relacionamento/enviar-email")')),

            $this->addBotaoHelper('btn-incluir btn-success btn btn-xs', 'glyphicon glyphicon-plus',
                'Incluir Modelo Email', '', '#modal_envia_email', true, array('data-toggle' => "modal", 'onclick' => '$("#modal_envia_email .modal-body").load("/relacionamento/incluir-modelo")')),


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
