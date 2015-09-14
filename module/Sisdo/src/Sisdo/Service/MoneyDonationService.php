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
use Sisdo\Constants\MoneyDonationConst;
use Sisdo\Dao\MoneyDonationDao;
use Sisdo\Entity\MoneyDonation;

class MoneyDonationService extends ServiceAbstract
{
    const URL_GET_DADOS = '/doacao-financeira/getDados';

    public function salvar(MoneyDonation $moneyDonation)
    {

        //var_dump();die;

        /** @var \Sisdo\Dao\MoneyDonationDao $dao */
        $dao = $this->getFromServiceLocator(MoneyDonationConst::DAO);
        $dao->save($moneyDonation);

        return $moneyDonation;
    }

    public function excluir($id)
    {
        /** @var \Sisdo\Dao\MoneyDonationDao $dao */
        $dao = $this->getFromServiceLocator(MoneyDonationConst::DAO);
        $moneyDonation = $dao->getEntity($id);
        return $dao->remove($moneyDonation);
    }

    public function getProduto($id)
    {
        /** @var MoneyDonationDao $dao */
        $dao = $this->getFromServiceLocator(MoneyDonationConst::DAO);
        return $dao->getEntity($id);
    }

    public function getGrid($isCollapse = false)
    {

        $jqgrid = new JqGridTable();
        $jqgrid->addColunas(array(JqGridConst::LABEL =>
            MoneyDonationConst::LBL_PERSON_USER_ID, JqGridConst::NAME => MoneyDonationConst::FLD_PERSON_USER_ID));
        $jqgrid->addColunas(array(JqGridConst::LABEL =>
            MoneyDonationConst::LBL_VALUE, JqGridConst::NAME => MoneyDonationConst::FLD_VALUE));
        $jqgrid->addColunas(array(JqGridConst::LABEL =>
            MoneyDonationConst::LBL_START_DATE, JqGridConst::NAME => MoneyDonationConst::FLD_START_DATE));
        $jqgrid->addColunas(array(JqGridConst::LABEL =>
            MoneyDonationConst::LBL_END_DATE, JqGridConst::NAME => MoneyDonationConst::FLD_END_DATE));
        $jqgrid->addColunas(array(JqGridConst::LABEL =>
            MoneyDonationConst::LBL_STATUS, JqGridConst::NAME => MoneyDonationConst::FLD_STATUS));

        $jqgrid->addColunas(array(JqGridConst::LABEL =>
            'Acao', JqGridConst::NAME => 'acao', JqGridConst::CLASSCSS => 'text-center'));

        $jqgrid->setUrl(self::URL_GET_DADOS);
        $jqgrid->setTitle('Doacoes pendente de confirmacao');

        $jqgrid->setCollapse($isCollapse);
        return $jqgrid->renderJs();
    }

    public function getGridDados()
    {

        /** @var MoneyDonationDao $dao */
        $dao = $this->getFromServiceLocator(MoneyDonationConst::DAO);

        /** @var \Application\Entity\User $instituicaoLogado */
        $instituicaoLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

        $qb = $dao->findMoneyDonationPendente($instituicaoLogado->getId());

        $jqgrid = new JqGridTable();
        $jqgrid->setAlias('m');
        $jqgrid->setQuery($qb);

        //$paramsPost = $jqgrid->getParametrosFromPost();
        $rows = $jqgrid->getDatatableArray();

        $dados = [];
        foreach ($rows[JqGridConst::PARAM_REGISTROS] as $row) {
            /** @var MoneyDonation $moneyDonation */
            $moneyDonation = $row;

            $temp[MoneyDonationConst::FLD_PERSON_USER_ID] = '';
            $temp[MoneyDonationConst::FLD_STATUS] = $moneyDonation->getStatus();
            $temp[MoneyDonationConst::FLD_START_DATE] = $moneyDonation->getStartdate();
            $temp[MoneyDonationConst::FLD_END_DATE] = $moneyDonation->getEnddate();
            $temp[MoneyDonationConst::FLD_VALUE] = $moneyDonation->getValue();

            $botaoEditar = new JqGridButton();
            $botaoEditar->setTitle('Editar');
            $botaoEditar->setClass('btn btn-primary btn-xs');
            $botaoEditar->setUrl('/doacao-financeira/editar/' . $moneyDonation->getId());
            $botaoEditar->setIcon('glyphicon glyphicon-edit');

            $botaoExcluir = new JqGridButton();
            $botaoExcluir->setTitle('Excluir');
            $botaoExcluir->setClass('btn btn-danger btn-xs');
            $botaoExcluir->setUrl('/doacao-financeira/excluir/' . $moneyDonation->getId());
            $botaoExcluir->setIcon('glyphicon glyphicon-trash');
            //$botaoExcluir->getOnClick();


            $temp[JqGridConst::ACAO] = "<div class='agrupa-botoes'>" . $botaoEditar->render() . $botaoExcluir->render() .
                "</div>";

            $dados[] = $temp;
        }
        $rows[JqGridConst::PARAM_REGISTROS] = $dados;

        return $rows;
    }


}