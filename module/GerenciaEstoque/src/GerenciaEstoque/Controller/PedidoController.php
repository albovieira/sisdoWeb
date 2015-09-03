<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace GerenciaEstoque\Controller;

use Application\Constants\FornecedorConst;
use Application\Constants\MensagemConst;
use Application\Constants\PedidoConst;
use Application\Custom\ActionControllerAbstract;
use GerenciaEstoque\Filter\ItemPedidoFilter;
use GerenciaEstoque\Filter\PedidoFilter;
use GerenciaEstoque\Form\ItemPedidoForm;
use GerenciaEstoque\Form\PedidoForm;
use GerenciaEstoque\Service\FornecedorService;
use GerenciaEstoque\Service\PedidoService;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class PedidoController extends ActionControllerAbstract
{
    public function indexAction()
    {
        /** @var PedidoService $service */
        $service = $this->getFromServiceLocator(PedidoConst::SERVICE);
        $grid = $service->getGrid();

        return new ViewModel(
            array(
                'grid' => $grid,
                'botoesHelper' => $this->getBotoesHelper()
            )
        );
    }

    public function getDadosAction()
    {

        /** @var PedidoService $service */
        $service = $this->getFromServiceLocator(PedidoConst::SERVICE);
        $grid = $service->getGridDados();

        return new JsonModel($grid);
    }

    public function incluirAction()
    {

        /** @var PedidoService $service */
        $service = $this->getFromServiceLocator(PedidoConst::SERVICE);

        $form = new PedidoForm($service);

        if ($this->getRequest()->isPost()) {

            $filter = new PedidoFilter();
            $post = $this->getRequest()->getPost();
            $form->setInputFilter($filter);
            $form->setData($post);

            if ($form->isValid()) {
                try {
                    if ($pedido = $service->salvar($form->getData())) {
                        $this->flashMessenger()->addSuccessMessage(MensagemConst::CADASTRO_SUCESSO);
                        //$form->get(PedidoConst::FLD_ID_PEDIDO)->setValue($pedido->getIdPedido());
                        $this->redirect()->toUrl('/pedido/editar/' . $pedido->getIdPedido());
                    } else {
                        $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                }
            }

        }


        $view = new ViewModel();
        $view->setTemplate('gerencia-estoque/pedido/formulario');
        $view->setVariables(array(
            'form' => $form,
            //'botoesHelper' => $this->getBotoesHelperToItemPedido(),
        ));
        return $view;
    }

    public function editarAction()
    {

        /** @var PedidoService $service */
        $service = $this->getFromServiceLocator(PedidoConst::SERVICE);

        $form = new PedidoForm($service);
        $filter = new PedidoFilter();

        $id = $this->params()->fromRoute('id');
        $pedido = $service->getEntity($id);

        //var_dump($pedido);die;
        $form->bind($pedido);
        $form->get(PedidoConst::FLD_DATA)->setValue($pedido->getData()->format('Y-m-d'));

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setInputFilter($filter);
            $form->setData($post);

            if ($form->isValid()) {
                try {
                    if ($service->salvar($form->getData(), $isEdit = null)) {
                        $this->flashMessenger()->addSuccessMessage(MensagemConst::CADASTRO_SUCESSO);
                        $form->setData($post);
                        //return $this->redirect()->toRoute('fornecedor');
                    } else {
                        $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);

                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                }
            }

        }

        $grid = $service->getGridItemPedido($id);


        $view = new ViewModel();
        $view->setTemplate('gerencia-estoque/pedido/formulario');
        $view->setVariables(array(
            'form' => $form,
            'grid' => $grid,
            'botoesHelper' => $this->getBotoesHelperToItemPedido(),
            'formItem' => $this->getFormItemPedido()
        ));
        return $view;
    }

    public function getDadosItemPedidoAction()
    {
        /** @var PedidoService $service */
        $service = $this->getFromServiceLocator(PedidoConst::SERVICE);
        $id = $this->params()->fromRoute('id');
        $grid = $service->getGridDadosItemPedido($id);

        return new JsonModel($grid);
    }

    public function getFormItemPedido()
    {
        /** @var PedidoService $service */
        $service = $this->getFromServiceLocator(PedidoConst::SERVICE);
        $form = new ItemPedidoForm($service, '/pedido/salvarItem');
        $filter = new ItemPedidoFilter();
        $form->setInputFilter($filter);

        return $form;

    }

    public function salvarItemAction()
    {
        var_dump('s item');
    }

    public function excluirAction()
    {
        /** @var FornecedorService $service */
        $service = $this->getFromServiceLocator(FornecedorConst::SERVICE);

        $id = $this->params()->fromRoute('id');
        try {
            if ($service->excluir($id)) {
                $this->flashMessenger()->addSuccessMessage(MensagemConst::EXCLUIR_SUCESSO);
            }
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
        }

        return $this->redirect()->toRoute('fornecedor');
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

    public function getBotoesHelper()
    {
        return array(
            $this->addBotaoHelper('btn-incluir btn-success btn btn-xs', 'glyphicon glyphicon-plus', 'Incluir Pedido', '',
                $this->url()->fromRoute('pedido', array('action' => 'incluir'))),
        );
    }

    public function getBotoesHelperToItemPedido()
    {
        return array(
            $this->addBotaoHelper('btn-incluir btn-success btn btn-xs', 'glyphicon glyphicon-plus', 'Incluir Item Pedido', '', false,
                '', array('data-toggle' => "modal", 'data-target' => "#modal_item"))
        );
    }

}
