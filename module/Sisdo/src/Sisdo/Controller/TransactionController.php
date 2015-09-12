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
use Sisdo\Constants\TransactionConst;
use Sisdo\Entity\StatusTransacao;
use Sisdo\Entity\Transaction;
use Sisdo\Form\TransactionForm;
use Sisdo\Service\TransactionService;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class TransactionController extends ActionControllerAbstract
{
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

        $form = new TransactionForm();

        $this->bindTransacao($transacao,$form);

        /** @var \Application\Entity\User $userLogado*/
        $userLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

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

        $form = new TransactionForm();

        $conversaAsArray = $transacao->getMessages()->getValues();

        /** @var \Application\Entity\User $userLogado*/
        $userLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

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

    public function bindTransacao(Transaction &$transacao, TransactionForm $form){

        $dataStart = $transacao->getStartDate()->format('d/m/Y');
        $transacao->setStartDate($dataStart);
        $dataEnd = $transacao->getEndDate()->format('d/m/Y');
        $transacao->setEndDate($dataEnd);

        $form->bind($transacao);
        $form->get(TransactionConst::FLD_PRODUTO)
            ->setValue($transacao->getProduct()->getTitle());
        $form->get(TransactionConst::FLD_STATUS)
            ->setValue(StatusTransacao::getStatusByFlag($transacao->getStatus()));
        $form->get(TransactionConst::FLD_PERSON_USER)
            ->setValue($transacao->getPersonUser()->getPerson()->getName());
        $form->get(TransactionConst::FLD_QUANTIFY)
            ->setValue($transacao->getQuantity());

        return $transacao;
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
