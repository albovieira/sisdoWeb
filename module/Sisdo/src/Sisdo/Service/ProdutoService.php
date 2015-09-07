<?php

namespace Sisdo\Service;
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 20/08/2015
 * Time: 00:11
 */

use Application\Constants\JqGridConst;
use Application\Custom\ServiceAbstract;
use Application\Util\JqGridButton;
use Application\Util\JqGridTable;
use Sisdo\Constants\ProdutoConst;
use Sisdo\Dao\ProdutoDao;
use Sisdo\Entity\Product;

class ProdutoService extends ServiceAbstract
{
    const URL_GET_DADOS = '/produto/getDados';

    public function salvar(Product $produto){

        /** @var \Sisdo\Dao\ProdutoDao $dao */
        $dao = $this->getFromServiceLocator(ProdutoConst::DAO);
        $dao->save($produto);

        return $produto;
    }

    public function excluir($produtoid)
    {
        /** @var \Sisdo\Dao\ProdutoDao $dao */
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
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            ProdutoConst::LBL_TITLE,JqGridConst::NAME => ProdutoConst::FLD_TITLE, JqGridConst::WIDTH => 150));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            ProdutoConst::LBL_DESC,JqGridConst::NAME  => ProdutoConst::FLD_DESC,JqGridConst::WIDTH => 300));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            ProdutoConst::LBL_QTD,JqGridConst::NAME => ProdutoConst::FLD_QTD, JqGridConst::WIDTH => 150));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            ProdutoConst::LBL_TIPO,JqGridConst::NAME => ProdutoConst::FLD_TIPO, JqGridConst::WIDTH => 150));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            ProdutoConst::LBL_DATE,JqGridConst::NAME => ProdutoConst::FLD_DATE, JqGridConst::WIDTH => 150));

        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            'Acao',JqGridConst::NAME => 'acao', JqGridConst::WIDTH => 80, JqGridConst::CLASSCSS => 'text-center'));

        $jqgrid->setUrl(self::URL_GET_DADOS);
        $jqgrid->setTitle('Doacoes cadastradas');

        $jqgrid->setCollapse(true);
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
            /** @var Product $produto */
            $produto = $row;

            $data = $produto->getDate();
            $temp[ProdutoConst::FLD_QTD] = $produto->getQuantity();
            $temp[ProdutoConst::FLD_TITLE] =  $produto->getTitle();
            $temp[ProdutoConst::FLD_DATE] = $data->format('d/m/Y');
            $temp[ProdutoConst::FLD_TIPO] = $produto->getProductType()->getDescription();
            $temp[ProdutoConst::FLD_DESC] = $produto->getDescription();

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