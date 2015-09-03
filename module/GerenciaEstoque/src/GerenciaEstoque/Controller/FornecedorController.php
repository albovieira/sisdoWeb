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
use Application\Custom\ActionControllerAbstract;
use GerenciaEstoque\Filter\FornecedorFilter;
use GerenciaEstoque\Form\FornecedorForm;
use GerenciaEstoque\Service\FornecedorService;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class FornecedorController extends ActionControllerAbstract
{
    public function indexAction()
    {
        /** @var FornecedorService S$service */
        $service = $this->getFromServiceLocator(FornecedorConst::SERVICE);
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

        /** @var FornecedorService $service */
        $service = $this->getFromServiceLocator(FornecedorConst::SERVICE);
        $grid = $service->getGridDados();

        return new JsonModel($grid);
    }

    public function incluirAction()
    {

        /** @var FornecedorService $service */
        $service = $this->getFromServiceLocator(FornecedorConst::SERVICE);

        $form = new FornecedorForm();

        if ($this->getRequest()->isPost()) {

            $filter = new FornecedorFilter();
            $post = $this->getRequest()->getPost();
            $form->setInputFilter($filter);
            $form->setData($post);

            if ($form->isValid()) {
                try {
                    if ($service->salvar($form->getData())) {
                        $this->flashMessenger()->addSuccessMessage(MensagemConst::CADASTRO_SUCESSO);

                        return $this->redirect()->toRoute('fornecedor');

                    } else {
                        $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                }
            }

        }

        $view = new ViewModel();
        $view->setTemplate('gerencia-estoque/fornecedor/formulario');
        $view->setVariables(array(
            'form' => $form,
        ));
        return $view;
    }

    public function editarAction()
    {

        /** @var FornecedorService $service */
        $service = $this->getFromServiceLocator(FornecedorConst::SERVICE);

        $form = new FornecedorForm();
        $filter = new FornecedorFilter();

        $id = $this->params()->fromRoute('id');
        $fornecedor = $service->getEntity($id);
        $form->bind($fornecedor);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setInputFilter($filter);
            $form->setData($post);

            if ($form->isValid()) {
                try {
                    if ($service->salvar($form->getData())) {
                        $this->flashMessenger()->addSuccessMessage(MensagemConst::CADASTRO_SUCESSO);
                        return $this->redirect()->toRoute('fornecedor');
                    } else {
                        $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);

                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                }
            }

        }

        $view = new ViewModel();
        $view->setTemplate('gerencia-estoque/fornecedor/formulario');
        $view->setVariables(array(
            'form' => $form,
        ));
        return $view;
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
            $this->addBotaoHelper('btn-incluir btn-success btn btn-xs', 'glyphicon glyphicon-plus', 'Incluir Fornecedor', '',
                $this->url()->fromRoute('fornecedor', array('action' => 'incluir'))),
        );
    }

}
