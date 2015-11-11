<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace SisdoWebService\Controller;

use Application\Custom\ActionControllerAbstract;
use Application\Entity\User;
use Sisdo\Constants\MessageConst;
use Sisdo\Constants\TransactionConst;
use Sisdo\Entity\Message;
use Sisdo\Entity\StatusTransacao;
use Sisdo\Entity\Transaction;
use Sisdo\Service\TransactionService;
use Zend\View\Model\JsonModel;

class TransactionController extends ActionControllerAbstract
{
    public function getTransactionsUserAction(){

        /** @var TransactionService $service */
        $service = $this->getFromServiceLocator(TransactionConst::SERVICE);

        //id usuario
        $id = $this->params()->fromQuery('id');

        /** @var Transaction $transacao */
        $transacoes = $service->getTransactionsByUser($id,false);

        $retorno = [];
        foreach($transacoes as $transacao){
            $retorno[$transacao->getId()][TransactionConst::FLD_ID_TRANSACTION] = $transacao->getId();
            $retorno[$transacao->getId()][TransactionConst::FLD_PRODUTO] = $transacao->getProduct()->getTitle();
            $retorno[$transacao->getId()][TransactionConst::FLD_START_DATE] = $transacao->getStartDate()->format('d/m/Y');
            $retorno[$transacao->getId()][TransactionConst::FLD_QUANTIFY] = $transacao->getQuantity();
            $retorno[$transacao->getId()][TransactionConst::FLD_PERSON_USER] = $transacao->getPersonUser()->getPerson()->getName();
            $retorno[$transacao->getId()][TransactionConst::FLD_INSTITUTION_USER] = $transacao->getInstitutionUser()->getInstituicao()->getFancyName();
            $retorno[$transacao->getId()][TransactionConst::FLD_STATUS] = StatusTransacao::getStatusByFlag($transacao->getStatus());
        }

        return new JsonModel($retorno);
    }

    public function getTransactionAction(){

        /** @var TransactionService $service */
        $service = $this->getFromServiceLocator(TransactionConst::SERVICE);

        $id = $this->params()->fromQuery('id');

        /** @var Transaction $transacao */
        $transacao = $service->getTransactionById($id);

        $retorno[TransactionConst::FLD_ID_TRANSACTION] = $transacao->getId();
        $retorno[TransactionConst::FLD_PRODUTO] = $transacao->getProduct()->getTitle();
        $retorno[TransactionConst::FLD_START_DATE] = $transacao->getStartDate()->format('d/m/Y');
        $retorno[TransactionConst::FLD_QUANTIFY] = $transacao->getQuantity();
        $retorno[TransactionConst::FLD_PERSON_USER] = $transacao->getPersonUser()->getPerson()->getName();
        $retorno[TransactionConst::FLD_INSTITUTION_USER] = $transacao->getInstitutionUser()->getInstituicao()->getFancyName();
        $retorno[TransactionConst::FLD_STATUS] = StatusTransacao::getStatusByFlag($transacao->getStatus());

        return new JsonModel($retorno);
    }

    public function newTransactionAction(){

        /** @var TransactionService $service */
        $service = $this->getFromServiceLocator(TransactionConst::SERVICE);

        $post = $this->params()->fromPost();
        $retorno =  $service->salvar($post);

        if($retorno instanceof Transaction){
            return new JsonModel($retorno->toArray());
        }
        return new JsonModel(array($retorno));
    }

    public function newMessageAction(){

        /** @var TransactionService $service */
        $service = $this->getFromServiceLocator(TransactionConst::SERVICE);

        $post = $this->params()->fromPost();
        $transacao =  $service->salvarMsg($post);
        if($transacao instanceof Transaction){
            $mensagens = $transacao->getMessages()->getValues();
            $retorno = [];
            /** @var Message $mensagem */
            foreach($mensagens as $mensagem){
                $retorno[$mensagem->getId()][MessageConst::FLD_DATE] = $mensagem->getDate()->format('d/m/Y h:m:s');
                $retorno[$mensagem->getId()][MessageConst::FLD_TRANSACAO] = $mensagem->getIdTransacao()->getId();
                $retorno[$mensagem->getId()][MessageConst::FLD_MENSAGEM] = $mensagem->getMessage();
                $retorno[$mensagem->getId()][MessageConst::FLD_USER] = $mensagem->getIdUser()->getId();

                /** @var User $user */
                $user = $mensagem->getIdUser();
                $isInstituicao = false;
                if($user->getInstituicao()){
                    $remetente = $user->getInstituicao()->getFancyName();
                    $foto = $user->getInstituicao()->getPicture();
                    $isInstituicao = true;
                }else{
                    $remetente = 'Eu';
                    $foto = $user->getPerson()->getPicture();
                }
                $retorno[$mensagem->getId()][MessageConst::FLD_USER_NAME] = $remetente;
                $retorno[$mensagem->getId()][MessageConst::FLD_FOTO] = $foto;
                $retorno[$mensagem->getId()]['isInstituicao'] = $isInstituicao;
            }

            return new JsonModel($retorno);
        }
        return new JsonModel(array($transacao));
    }

    public function getMensagensAction(){

        /** @var TransactionService $service */
        $service = $this->getFromServiceLocator(TransactionConst::SERVICE);

        $id = $this->params()->fromQuery('id');

        /** @var Transaction $transacao */
        $transacao = $service->getTransactionById($id);

        $mensagens = $transacao->getMessages()->getValues();
        $retorno = [];
        /** @var Message $mensagem */
        foreach($mensagens as $mensagem){
            $retorno[$mensagem->getId()][MessageConst::FLD_DATE] = $mensagem->getDate()->format('d/m/Y h:m:s');
            $retorno[$mensagem->getId()][MessageConst::FLD_TRANSACAO] = $mensagem->getIdTransacao()->getId();
            $retorno[$mensagem->getId()][MessageConst::FLD_MENSAGEM] = $mensagem->getMessage();
            $retorno[$mensagem->getId()][MessageConst::FLD_USER] = $mensagem->getIdUser()->getId();

            /** @var User $user */
            $user = $mensagem->getIdUser();
            $isInstituicao = false;
            if($user->getInstituicao()){
                $remetente = $user->getInstituicao()->getFancyName();
                $foto = $user->getInstituicao()->getPicture();
                $isInstituicao = true;
            }else{
                $remetente = 'Eu';
                $foto = $user->getPerson()->getPicture();
            }
            $retorno[$mensagem->getId()][MessageConst::FLD_USER_NAME] = $remetente;
            $retorno[$mensagem->getId()][MessageConst::FLD_FOTO] = $foto;
            $retorno[$mensagem->getId()]['isInstituicao'] = $isInstituicao;
        }
        return new JsonModel($retorno);
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


}
