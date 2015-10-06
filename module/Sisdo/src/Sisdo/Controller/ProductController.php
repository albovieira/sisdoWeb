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
use Sisdo\Constants\ProductConst;
use Sisdo\Entity\Product;
use Sisdo\Filter\ProdutoFilter;
use Sisdo\Service\ProductService;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ProductController extends ActionControllerAbstract
{
    protected $title = 'Doações';

    public function indexAction()
    {
        $this->title = array($this->title,'Lista');

        /** @var ProductService $service */
        $service = $this->getFromServiceLocator(ProductConst::SERVICE);
        $grid = $service->getGrid();
        return new ViewModel(
            array(
                'grid' => $grid,
                'botoesHelper' => $this->getBotoesHelper()
            )
        );
    }

    public function getDadosAction(){

        /** @var ProductService $service */
        $service = $this->getFromServiceLocator(ProductConst::SERVICE);
        $grid = $service->getGridDados();

        return new JsonModel($grid);
    }

    public function incluirAction(){

        $this->title = array($this->title,'Incluir');

        /** @var ProductService $service */
        $service = $this->getFromServiceLocator(ProductConst::SERVICE);

        /** @var \Application\Entity\User $userLogado*/
        $userLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();
        $form = new \Sisdo\Form\ProductForm(null, $userLogado, $service);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setInputFilter(new ProdutoFilter());
            $form->setData($post);

            if($form->isValid()){
                try{

                    /** @var Product $produto */
                    $produto = $form->getData();
                    $produto->setInstitutionUser($userLogado);

                    if($service->salvar($produto)){
                        $this->flashMessenger()->addSuccessMessage(MensagemConst::CADASTRO_SUCESSO);

                        return $this->redirect()->toRoute('produto');

                    } else {
                        $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                    }
                }
                catch(\Exception $e){
                    $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                }
            }

        }

        $view = new ViewModel();
        $view->setTemplate('sisdo/product/formulario');
        $view->setVariables(array(
            'form' => $form,
        ));
        return $view;
    }

    public function editarAction(){

        $this->title = array($this->title,'Editar');

        /** @var ProductService $service */
        $service = $this->getFromServiceLocator(ProductConst::SERVICE);

        /** @var \Application\Entity\User $userLogado*/
        $userLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

        $form = new \Sisdo\Form\ProductForm(null, $userLogado, $service);

        $id = $this->params()->fromRoute('id');
        /** @var Product $produto */
        $produto = $service->getProduto($id);
        $form->bind($produto);

        $form->get(ProductConst::FLD_DATE)->setValue($produto->getDate()->format('Y-m-d'));


        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setInputFilter(new ProdutoFilter());
            $form->setData($post);

            if ($form->isValid()) {
                try {
                    $produto->setInstitutionUser($userLogado);
                    if ($service->salvar($produto)) {
                        $this->flashMessenger()->addSuccessMessage(MensagemConst::CADASTRO_SUCESSO);
                        return $this->redirect()->toRoute('produto');
                    } else {
                        $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);

                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                }
            }

        }

        $view = new ViewModel();
        $view->setTemplate('sisdo/product/formulario');
        $view->setVariables(array(
            'form' => $form,
        ));
        return $view;
    }

    public function excluirAction(){
        /** @var ProductService $service */
        $service = $this->getFromServiceLocator(ProductConst::SERVICE);

        $id = $this->params()->fromRoute('id');
        try {
            if ($service->excluir($id)) {
                $this->flashMessenger()->addSuccessMessage(MensagemConst::EXCLUIR_SUCESSO);
            }
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
        }

        return $this->redirect()->toRoute('produto');
    }

    public function getDoacoesPessoaAction(){
        /** @var ProductService $service */
        $service = $this->getFromServiceLocator(ProductConst::SERVICE);

    }

    /**
     * Retorna o titulo da pagina (especializar)
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
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
            $this->addBotaoHelper('btn-incluir btn-success btn btn-xs', 'glyphicon glyphicon-plus', 'Incluir Doacao','',
                $this->url()->fromRoute('produto', array('action' => 'incluir'))),
        );
    }

}
