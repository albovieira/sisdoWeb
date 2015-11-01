<?php

namespace Sisdo\Service;
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 20/08/2015
 * Time: 00:11
 */

use Application\Constants\FormConst;
use Application\Constants\JqGridConst;
use Application\Constants\UsuarioConst;
use Application\Custom\ServiceAbstract;
use Application\Util\JqGridButton;
use Application\Util\JqGridTable;
use Sisdo\Constants\ProductConst;
use Sisdo\Constants\StatusTransacaoConst;
use Sisdo\Constants\TransactionConst;
use Sisdo\Dao\TransactionDao;
use Sisdo\Entity\StatusTransacao;
use Sisdo\Entity\Transaction;
use Sisdo\Entity\Unidade;

class TransactionService extends ServiceAbstract
{
    const URL_GET_DADOS = '/transacao/getDados';

    public function getGrid(){

        $jqgrid = new JqGridTable();
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            TransactionConst::LBL_PRODUTO,JqGridConst::NAME => TransactionConst::FLD_PRODUTO));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            TransactionConst::LBL_PERSON_USER_ID,JqGridConst::NAME => TransactionConst::FLD_PERSON_USER));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            TransactionConst::LBL_STATUS,JqGridConst::NAME => TransactionConst::FLD_STATUS));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            TransactionConst::LBL_QUANTIFY,JqGridConst::NAME => TransactionConst::FLD_QUANTIFY));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            TransactionConst::LBL_START_DATE,JqGridConst::NAME => TransactionConst::FLD_START_DATE));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            TransactionConst::LBL_END_DATE,JqGridConst::NAME => TransactionConst::FLD_END_DATE));

        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            'Acao',JqGridConst::NAME => 'acao', JqGridConst::CLASSCSS => 'text-center'));

        $jqgrid->setUrl(self::URL_GET_DADOS);
        $jqgrid->setTitle('Transacoes Pendentes');

        return $jqgrid->renderJs();
    }

    public function getGridDados(){

        /** @var TransactionDao $dao */
        $dao = $this->getFromServiceLocator(TransactionConst::DAO);

        /** @var \Application\Entity\User $instituicaoLogado */
        $instituicaoLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

        $qb = $dao->findTransactionsPendentes($instituicaoLogado->getId());

        $jqgrid = new JqGridTable();
        $jqgrid->setAlias('r');
        $jqgrid->setQuery($qb);

        //$paramsPost = $jqgrid->getParametrosFromPost();
        $rows = $jqgrid->getDatatableArray();

        $dados = [];
        foreach($rows[JqGridConst::PARAM_REGISTROS] as $row){
            /** @var Transaction $transaction */
            $transaction = $row;

            $temp[TransactionConst::FLD_PRODUTO] = $transaction->getProduct()->getTitle();
            $temp[TransactionConst::FLD_PERSON_USER] = $transaction->getPersonUser()->getPerson()->getName();
            $temp[TransactionConst::FLD_STATUS] = StatusTransacao::getStatusByFlag($transaction->getStatus());
            $temp[TransactionConst::FLD_QUANTIFY] = $transaction->getQuantity() . ' ('. Unidade::getUnidadeBySigla($transaction->getProduct()->getUnity()) . ')';
            $temp[TransactionConst::FLD_START_DATE] = $transaction->getStartDate()->format('d/m/Y');

            $ano = $transaction->getEndDate()->format('Y');
            if($ano != FormConst::DATA_INVALIDA){
                $dataEnd = $transaction->getEndDate()->format('d/m/Y');
            }else{
                $dataEnd = '';
            }
            $temp[TransactionConst::FLD_END_DATE] = $dataEnd;


            $botaoVer = new JqGridButton();
            $botaoVer->setTitle('Ver transacao');
            $botaoVer->setClass('btn btn-primary btn-xs');
            $botaoVer->setUrl('#modal_transacao');
            $botaoVer->setIcon('glyphicon glyphicon-eye-open');
            $botaoVer->setDataToggle('modal');
            $botaoVer->setOnClick("$('#modal_transacao .modal-body').load('/transacao/visualizar/{$transaction->getId()}')");

            $botaoConfirmar = new JqGridButton();
            $botaoConfirmar->setTitle('Confirmar Recebimento');
            $botaoConfirmar->setClass('btn btn-success btn-xs');
            $botaoConfirmar->setUrl('/transacao/confirmar-recebimento/' . $transaction->getId());
            $botaoConfirmar->setIcon('glyphicon glyphicon-ok-sign');


            $botaoCancelar = new JqGridButton();
            $botaoCancelar->setTitle('Cancelar Recebimento');
            $botaoCancelar->setClass('btn btn-danger btn-xs');
            $botaoCancelar->setUrl('/transacao/cancelar-recebimento/' . $transaction->getId());
            $botaoCancelar->setIcon('glyphicon glyphicon-ban-circle');

            $temp[JqGridConst::ACAO] = "<div class='agrupa-botoes'>". $botaoVer->render() . $botaoConfirmar->render() . $botaoCancelar->render() .
                "</div>";

            $dados[] = $temp;
        }
        $rows[JqGridConst::PARAM_REGISTROS] = $dados;

        return $rows;
    }

    public function salvar($transacao){

        /** @var TransactionDao $dao */
        $dao = $this->getFromServiceLocator(TransactionConst::DAO);


        if(isset($transacao[TransactionConst::FLD_ID_TRANSACTION])){

        }else{
            /** @var Transaction $transacaoObj */
            $transacaoObj = $dao->getEntity();
            $dataIni = new \DateTime('now');
            $transacaoObj->setStartDate($dataIni);
            $transacaoObj->setQuantity($transacao[TransactionConst::FLD_QUANTIFY]);
            $transacaoObj->setStatus(StatusTransacaoConst::FLAG_PENDENTE_FINALIZACAO);
            $transacaoObj->setProduct($this->getFromServiceLocator(ProductConst::DAO)->getEntity($transacao[TransactionConst::FLD_PRODUTO]));
            $transacaoObj->setInstitutionUser($this->getFromServiceLocator(UsuarioConst::SERVICE)->findById($transacao[TransactionConst::FLD_INSTITUTION_USER]));
            $transacaoObj->setPersonUser($this->getFromServiceLocator(UsuarioConst::SERVICE)->findById($transacao[TransactionConst::FLD_PERSON_USER]));
            $transacaoObj->setShippingMethod($transacao[TransactionConst::FLD_SHIPPING_METHOD]);

            return $dao->save($transacaoObj);
        }
        return false;

    }

    public function finalizaTransacao($id){

        /** @var TransactionDao $dao */
        $dao = $this->getFromServiceLocator(TransactionConst::DAO);

        /** @var Transaction $transacao */
        $transacao = $this->getTransactionById($id);

        if($transacao->getStatus() == StatusTransacaoConst::FLAG_PENDENTE_FINALIZACAO){
            $transacao->setStatus(StatusTransacaoConst::FLAG_FINALIZADO);
            $transacao->setEndDate(new \DateTime('now'));

            $dao->save($transacao);

            return true;
        }
        return false;
    }


    public function getTransactionsByUser($userid){
        /** @var TransactionDao $dao */
        $dao = $this->getFromServiceLocator(TransactionConst::DAO);
        return $dao->getRepository(
            $dao->getEntityName())
            ->findBy(array("institutionUser" => $userid,"status" => StatusTransacaoConst::FLAG_PENDENTE_FINALIZACAO)
            );
    }

    public function getAllTransactionsByUser($userid){
        /** @var TransactionDao $dao */
        $dao = $this->getFromServiceLocator(TransactionConst::DAO);
        return $dao->getRepository(
            $dao->getEntityName())
            ->findBy(array("institutionUser" => $userid)
            );
    }

    public function getTransactionsFinalizadasByUser($userid){
        /** @var TransactionDao $dao */
        $dao = $this->getFromServiceLocator(TransactionConst::DAO);
        return $dao->getRepository(
            $dao->getEntityName())
            ->findBy(array("institutionUser" => $userid,"status" => StatusTransacaoConst::FLAG_FINALIZADO)
            );
    }


    public function getTransactionById($id){
        /** @var TransactionDao $dao */
        $dao = $this->getFromServiceLocator(TransactionConst::DAO);
        return $dao->getEntity($id);
    }


}