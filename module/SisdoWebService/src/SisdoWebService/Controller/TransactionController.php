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
use Sisdo\Constants\TransactionConst;
use Sisdo\Entity\Transaction;
use Sisdo\Service\TransactionService;
use Zend\View\Model\JsonModel;

class TransactionController extends ActionControllerAbstract
{
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
