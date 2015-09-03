<?php

namespace GerenciaEstoque\Service;
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 20/08/2015
 * Time: 00:11
 */
use Application\Constants\JqGridConst;
use Application\Constants\ProdutoConst;
use Application\Custom\ServiceAbstract;
use Application\Util\JqGridButton;
use Application\Util\JqGridTable;
use GerenciaEstoque\Dao\ProdutoDao;
use GerenciaEstoque\Entity\Produto;

//use Application\Util\JqGridTable;

class ProdutoService extends ServiceAbstract
{
    const URL_GET_DADOS = '/produto/getDados';

    public function salvar(Produto $produto){

        /** @var ProdutoDao $dao */
        $dao = $this->getFromServiceLocator(ProdutoConst::DAO);
        $dao->save($produto);

        return $produto;
    }

    public function excluir($produtoid)
    {
        /** @var ProdutoDao $dao */
        $dao = $this->getFromServiceLocator(ProdutoConst::DAO);
        $produto = $dao->getEntity($produtoid);
        return $dao->remove($produto);
    }

    public function getProduto($id)
    {
        /** @var ProdutoDao $dao */
        $dao = $this->getFromServiceLocator(ProdutoConst::DAO);
        return $dao->getEntity($id);
    }

    public function getGrid(){

        $jqgrid = new JqGridTable();
        $jqgrid->addColunas(array(JqGridConst::LABEL =>
            ProdutoConst::LBL_ID_PRODUTO,JqGridConst::NAME => ProdutoConst::FLD_ID_PRODUTO, JqGridConst::WIDTH => 200));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            ProdutoConst::LBL_DESC_PRODUTO,JqGridConst::NAME  => ProdutoConst::FLD_DESC_PRODUTO,JqGridConst::WIDTH => 400));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            ProdutoConst::LBL_VAL_UNITARIO,JqGridConst::NAME => ProdutoConst::FLD_VAL_UNITARIO, JqGridConst::WIDTH => 150));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            'Acao',JqGridConst::NAME => 'acao', JqGridConst::WIDTH => 60, JqGridConst::CLASSCSS => 'text-center'));

        $jqgrid->setUrl(self::URL_GET_DADOS);
        $jqgrid->setTitle('Produto');

        return $jqgrid->renderJs();
    }

    public function getGridDados(){

        /** @var ProdutoDao $dao */
        $dao = $this->getFromServiceLocator(ProdutoConst::DAO);

        $qb = $dao->getCompleteQueryBuilder();

        $jqgrid = new JqGridTable();
        $jqgrid->setAlias('p');
        $jqgrid->setQuery($qb);

        //$paramsPost = $jqgrid->getParametrosFromPost();
        $rows = $jqgrid->getDatatableArray();

        $dados = [];
        foreach($rows[JqGridConst::PARAM_REGISTROS] as $row){
            /** @var Produto $produto */
            $produto = $row;
            $temp[ProdutoConst::FLD_ID_PRODUTO] = (string) $produto->getIdProduto();
            $temp[ProdutoConst::FLD_DESC_PRODUTO] = $produto->getDescricaoProduto();
            $temp[ProdutoConst::FLD_VAL_UNITARIO] = (string) $produto->getValorUnitario();

            $botaoEditar = new JqGridButton();
            $botaoEditar->setTitle('Editar');
            $botaoEditar->setClass('btn btn-primary btn-xs');
            $botaoEditar->setUrl('/produto/editar/' . $produto->getIdProduto());
            $botaoEditar->setIcon('glyphicon glyphicon-edit');

            $botaoExcluir = new JqGridButton();
            $botaoExcluir->setTitle('Excluir');
            $botaoExcluir->setClass('btn btn-danger btn-xs');
            $botaoExcluir->setUrl('/produto/excluir/' . $produto->getIdProduto());
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