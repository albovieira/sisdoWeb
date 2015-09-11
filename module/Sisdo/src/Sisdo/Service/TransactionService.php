<?php

namespace Sisdo\Service;
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 20/08/2015
 * Time: 00:11
 */

use Application\Constants\JqGridConst;
use Application\Constants\UsuarioConst;
use Application\Custom\ServiceAbstract;
use Application\Util\JqGridButton;
use Application\Util\JqGridTable;
use Sisdo\Constants\TransactionConst;
use Sisdo\Dao\TransactionDao;
use Sisdo\Entity\StatusTransacao;
use Sisdo\Entity\Transaction;

class TransactionService extends ServiceAbstract
{
    const URL_GET_DADOS = '/transacao/getDados';


    public function getTransactionById($id){
        /** @var TransactionDao $dao */
        $dao = $this->getFromServiceLocator(TransactionConst::DAO);
        return $dao->getEntity($id);
    }


    public function getGrid(){

        $jqgrid = new JqGridTable();
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            TransactionConst::LBL_PRODUTO,JqGridConst::NAME => TransactionConst::FLD_PRODUTO, JqGridConst::WIDTH => 150));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            TransactionConst::LBL_PERSON_USER_ID,JqGridConst::NAME => TransactionConst::FLD_PERSON_USER, JqGridConst::WIDTH => 120));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            TransactionConst::LBL_STATUS,JqGridConst::NAME => TransactionConst::FLD_STATUS, JqGridConst::WIDTH => 140));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            TransactionConst::LBL_QUANTIFY,JqGridConst::NAME => TransactionConst::FLD_QUANTIFY, JqGridConst::WIDTH => 100));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            TransactionConst::LBL_START_DATE,JqGridConst::NAME => TransactionConst::FLD_START_DATE, JqGridConst::WIDTH => 80));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            TransactionConst::LBL_END_DATE,JqGridConst::NAME => TransactionConst::FLD_END_DATE, JqGridConst::WIDTH => 80));

        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            'Acao',JqGridConst::NAME => 'acao', JqGridConst::WIDTH => 120, JqGridConst::CLASSCSS => 'text-center'));

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
            $temp[TransactionConst::FLD_QUANTIFY] = $transaction->getQuantity();
            $temp[TransactionConst::FLD_START_DATE] = $transaction->getStartDate()->format('d/m/Y');
            $temp[TransactionConst::FLD_END_DATE] = $transaction->getEndDate()->format('d/m/Y');


            $botaoVer = new JqGridButton();
            $botaoVer->setTitle('Ver transacao');
            $botaoVer->setClass('btn btn-primary btn-xs');
            $botaoVer->setUrl('#modal_transacao');
            $botaoVer->setIcon('glyphicon glyphicon-zoom-in');
            $botaoVer->setDataToggle('modal');
            $botaoVer->setOnClick("$('#modal_transacao .modal-body').load('/transacao/visualizar/{$transaction->getId()}')");

            $botaoConfirmar = new JqGridButton();
            $botaoConfirmar->setTitle('Confirmar Recebimento');
            $botaoConfirmar->setClass('btn btn-success btn-xs');
            $botaoConfirmar->setUrl('/relacionamento/editar/' . $transaction->getId());
            $botaoConfirmar->setIcon('glyphicon glyphicon-ok-sign');


            $botaoCancelar = new JqGridButton();
            $botaoCancelar->setTitle('Cancelar Recebimento');
            $botaoCancelar->setClass('btn btn-danger btn-xs');
            $botaoCancelar->setUrl('/relacionamento/excluir/' . $transaction->getId());
            $botaoCancelar->setIcon('glyphicon glyphicon-ban-circle');
            //$botaoExcluir->getOnClick();


            $temp[JqGridConst::ACAO] = "<div class='agrupa-botoes'>". $botaoVer->render() . $botaoConfirmar->render() . $botaoCancelar->render() .
                "</div>";

            $dados[] = $temp;
        }
        $rows[JqGridConst::PARAM_REGISTROS] = $dados;

        return $rows;
    }


}