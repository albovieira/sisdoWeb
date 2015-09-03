<?php

namespace GerenciaEstoque\Service;

/**
 * Created by PhpStorm.
 * User: albov
 * Date: 20/08/2015
 * Time: 00:11
 */
use Application\Constants\FornecedorConst;
use Application\Constants\JqGridConst;
use Application\Custom\ServiceAbstract;
use Application\Util\JqGridButton;
use Application\Util\JqGridTable;
use GerenciaEstoque\Dao\FornecedorDao;
use GerenciaEstoque\Entity\Fornecedor;

//use Application\Util\JqGridTable;

class FornecedorService extends ServiceAbstract
{
    const URL_GET_DADOS = '/fornecedor/getDados';

    public function salvar(\GerenciaEstoque\Entity\Fornecedor $fornecedor)
    {


        /** @var FornecedorDao $dao */
        $dao = $this->getFromServiceLocator(FornecedorConst::DAO);
        $dao->save($fornecedor);

        return $fornecedor;
    }

    public function excluir($id)
    {
        /** @var FornecedorDao $dao */
        $dao = $this->getFromServiceLocator(FornecedorConst::DAO);
        $fornecedor = $dao->getEntity($id);
        return $dao->remove($fornecedor);
    }

    public function getEntity($id)
    {
        /** @var FornecedorDao $dao */
        $dao = $this->getFromServiceLocator(FornecedorConst::DAO);
        return $dao->getEntity($id);
    }

    public function getGrid()
    {

        $jqgrid = new JqGridTable();
        $jqgrid->addColunas(array(JqGridConst::LABEL =>
            FornecedorConst::LBL_ID_FORNEC, JqGridConst::NAME => FornecedorConst::FLD_ID_FORNEC, JqGridConst::WIDTH => 200));
        $jqgrid->addColunas(array(JqGridConst::LABEL =>
            FornecedorConst::LBL_NOME_FORNEC, JqGridConst::NAME => FornecedorConst::FLD_NOME_FORNEC, JqGridConst::WIDTH => 400));
        $jqgrid->addColunas(array(JqGridConst::LABEL =>
            FornecedorConst::LBL_CNPJ, JqGridConst::NAME => FornecedorConst::FLD_CNPJ, JqGridConst::WIDTH => 150));
        $jqgrid->addColunas(array(JqGridConst::LABEL =>
            'Acao', JqGridConst::NAME => 'acao', JqGridConst::WIDTH => 60, JqGridConst::CLASSCSS => 'text-center'));

        $jqgrid->setUrl(self::URL_GET_DADOS);
        $jqgrid->setTitle('Fornecedor');

        return $jqgrid->renderJs();
    }

    public function getGridDados()
    {

        /** @var FornecedorDao $dao */
        $dao = $this->getFromServiceLocator(FornecedorConst::DAO);

        $qb = $dao->getCompleteQueryBuilder();

        $jqgrid = new JqGridTable();
        $jqgrid->setAlias('f');
        $jqgrid->setQuery($qb);

        //$paramsPost = $jqgrid->getParametrosFromPost();
        $rows = $jqgrid->getDatatableArray();

        $dados = [];
        foreach ($rows[JqGridConst::PARAM_REGISTROS] as $row) {
            /** @var Fornecedor $fornecedor */
            $fornecedor = $row;
            $idFornecedor = $fornecedor->getIdFornecedor();
            $temp[FornecedorConst::FLD_ID_FORNEC] = (string)$idFornecedor;
            $temp[FornecedorConst::FLD_NOME_FORNEC] = $fornecedor->getNomeFornecedor();
            $temp[FornecedorConst::FLD_CNPJ] = (string)$fornecedor->getCnpj();

            $botaoEditar = new JqGridButton();
            $botaoEditar->setTitle('Editar');
            $botaoEditar->setClass('btn btn-primary btn-xs');
            $botaoEditar->setUrl('/fornecedor/editar/' . $idFornecedor);
            $botaoEditar->setIcon('glyphicon glyphicon-edit');

            $botaoExcluir = new JqGridButton();
            $botaoExcluir->setTitle('Excluir');
            $botaoExcluir->setClass('btn btn-danger btn-xs');
            $botaoExcluir->setUrl('/fornecedor/excluir/' . $idFornecedor);
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