<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Sisdo\Controller;

use Application\Constants\UsuarioConst;
use Application\Custom\ActionControllerAbstract;
use Sisdo\Constants\ProductConst;
use Sisdo\Constants\TransactionConst;
use Sisdo\Entity\Product;
use Sisdo\Entity\ShippingMethod;
use Sisdo\Entity\StatusTransacao;
use Sisdo\Entity\Transaction;
use Sisdo\Form\MessageForm;
use Sisdo\Form\TransactionForm;
use Sisdo\Form\TransactionUserForm;
use Sisdo\Service\ProductService;
use Sisdo\Service\TransactionService;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class TransactionController extends ActionControllerAbstract
{
    const DATA_INVALIDA = '-0001';

    public function indexAction()
    {
        /** @var TransactionService $service */
        $service = $this->getFromServiceLocator(TransactionConst::SERVICE);
        $grid = $service->getGrid();
        return new ViewModel(
            array(
                'grid' => $grid,
                'botoesHelper' => $this->getBotoesHelper()
            )
        );
    }

    public function getDadosAction(){

        /** @var TransactionService $service */
        $service = $this->getFromServiceLocator(TransactionConst::SERVICE);
        $grid = $service->getGridDados();

        return new JsonModel($grid);
    }

    public function confirmarRecebimentoAction(){

        /** @var TransactionService $service */
        $service = $this->getFromServiceLocator(TransactionConst::SERVICE);

        $id = $this->params()->fromRoute('id');
        /** @var Transaction $transacao */
        $transacao = $service->getTransactionById($id);

        $conversaAsArray = $transacao->getMessages()->getValues();

        /** @var \Application\Entity\User $userLogado*/
        $userLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

        $form = new TransactionForm(null,$userLogado);

        $this->bindTransacao($transacao,$form);

        $view = new ViewModel();
        $view->setVariables(
            array(
                'form' => $form,
                'transacao' => $transacao,
                'userLogado' => $userLogado,
                'conversa' => $conversaAsArray,
            )
        );
        return $view;
    }

    public function visualizarAction(){
        /** @var TransactionService $service */
        $service = $this->getFromServiceLocator(TransactionConst::SERVICE);

        $id = $this->params()->fromRoute('id');

        /** @var Transaction $transacao */
        $transacao = $service->getTransactionById($id);

        /** @var \Application\Entity\User $userLogado*/
        $userLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

        $form = new TransactionForm(null,$userLogado);

        $conversaAsArray = $transacao->getMessages()->getValues();


        $this->bindTransacao($transacao,$form);

        $view = new ViewModel();
        $view->setVariables(
            array(
                'form' => $form,
                'transacao' => $transacao,
                'conversa' => $conversaAsArray,
                'userLogado' => $userLogado
            )
        );
        $view->setTerminal(true);

        return $view;
    }

    public function doarAction(){

        /** @var TransactionService $service */
        $service = $this->getFromServiceLocator(TransactionConst::SERVICE);

        /** @var ProductService $serviceProduct */
        $serviceProduct = $this->getFromServiceLocator(ProductConst::SERVICE);

        var_dump($this->getRequest()->getPost());die;
        $id = $this->params()->fromRoute('id');

        /** @var Product $produto */
        $produto = $serviceProduct->getProduto($id);
        /** @var \Application\Entity\User $userLogado*/
        $userLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

        $form = new TransactionUserForm($service,$userLogado,false,$id);
        $form->get(TransactionConst::FLD_INSTITUTION_USER)->setValue($produto->getInstitutionUser()->getId());

        $formMessage = new MessageForm(null,$userLogado);


        return new ViewModel(array(
            'form' =>  $form,
            'formMessage' =>  $formMessage,
            'produtoAtual' =>  $produto
        ));
    }

    public function finalizarTransacaoAction(){
        /** @var TransactionService $service */
        $service = $this->getFromServiceLocator(TransactionConst::SERVICE);

        $id = $this->getRequest()->getQuery('id');

        /** @var Transaction $transacao */
        $isFinalizado = $service->finalizaTransacao($id);

        if($isFinalizado){
            $retorno = 'sucesso';
        }else{
            $retorno = 'falha';
        }

        return new JsonModel(array('retorno' => $retorno));
    }

    public function bindTransacao(Transaction &$transacao, TransactionForm $form){

        $dataStart = $transacao->getStartDate()->format('d/m/Y');
        $transacao->setStartDate($dataStart);

        $ano = $transacao->getEndDate()->format('Y');
        if($ano != self::DATA_INVALIDA){
            $dataEnd = $transacao->getEndDate()->format('d/m/Y');
            $transacao->setEndDate($dataEnd);
        }else{
            $transacao->setEndDate(null);
        }

        $form->bind($transacao);

        $institutionUser = $form->get(TransactionConst::FLD_INSTITUTION_USER)->getValue();

        $form->get(TransactionConst::FLD_PRODUTO)
            ->setValue($transacao->getProduct()->getTitle());
        $form->get(TransactionConst::FLD_STATUS)
            ->setValue(StatusTransacao::getStatusByFlag($transacao->getStatus()));
        $form->get(TransactionConst::FLD_SHIPPING_METHOD)
            ->setValue(ShippingMethod::getShippingMethod($transacao->getShippingMethod()));
        $form->get(TransactionConst::FLD_PERSON_USER)
            ->setValue($transacao->getPersonUser()->getPerson()->getName());
        $form->get(TransactionConst::FLD_QUANTIFY)
            ->setValue($transacao->getQuantity());
        $form->get(TransactionConst::FLD_INSTITUTION_USER)
            ->setValue($institutionUser->getId());

        return $transacao;
    }

    /**
     * Retorna o titulo da pagina (especializar)
     *
     * @return mixed
     */
    public function getTitle()
    {

    }

    /**
     * @return mixed
     */
    public function getBreadcrumb()
    {
        // TODO: Implement getBreadcrumb() method.
    }


}
