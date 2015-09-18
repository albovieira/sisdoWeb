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
use Application\Custom\EntityAbstract;
use Application\Custom\ServiceAbstract;
use Application\Util\JqGridButton;
use Application\Util\JqGridTable;
use Sisdo\Constants\ContactConst;
use Sisdo\Constants\InstitutionConst;
use Sisdo\Constants\ProductConst;
use Sisdo\Dao\ProductDao;
use Sisdo\Entity\Adress;
use Sisdo\Entity\Contact;
use Sisdo\Entity\Institution;
use Sisdo\Entity\Product;
use Sisdo\Entity\Unidade;

class InstitutionService extends ServiceAbstract
{
    const URL_GET_DADOS = '/instituicao/getDados';


    public function getInstitutionById($id){
        /** @var \Sisdo\Dao\InstitutionDao $dao */
        $dao = $this->getFromServiceLocator(InstitutionConst::DAO);

        $dao->getEntity($id);
        return $dao->getEntity($id);

    }

    public function salvar(EntityAbstract $obj){

        $dao = '';
        if ($obj instanceof Institution) {
            /** @var \Sisdo\Dao\InstitutionDao $dao */
            $dao = $this->getFromServiceLocator(InstitutionConst::DAO);
        }
        else if($obj instanceof Adress){
            /** @var \Sisdo\Dao\AdressDao $dao */
            $dao = $this->getFromServiceLocator(InstitutionConst::DAO);
        }
        else if($obj instanceof Contact){
            /** @var \Sisdo\Dao\ContactDao $dao */
            $dao = $this->getFromServiceLocator(ContactConst::DAO);
        }
        else{
            return false;
        }

        $userLogado = $this->getUserLogado();
        $obj->setUserId($userLogado);

        if($obj->getId()){
            $em = $dao->getEntityManager();
            $em->merge($obj);
            $em->flush();
            return $obj;
        }
        $dao->save($obj);
        return $obj;
    }

    public function excluir($institutionId)
    {
        /** @var \Sisdo\Dao\ProductDao $dao */
        $dao = $this->getFromServiceLocator(ProductConst::DAO);
        $institution = $dao->getEntity($institutionId);
        return $dao->remove($institution);
    }



    public function getGrid(){

        $jqgrid = new JqGridTable();
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            ProductConst::LBL_TITLE,JqGridConst::NAME => ProductConst::FLD_TITLE, JqGridConst::WIDTH => 150));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            ProductConst::LBL_DESC,JqGridConst::NAME  => ProductConst::FLD_DESC,JqGridConst::WIDTH => 300));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            ProductConst::LBL_QTD,JqGridConst::NAME => ProductConst::FLD_QTD, JqGridConst::WIDTH => 150));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            ProductConst::LBL_UNITY,JqGridConst::NAME => ProductConst::FLD_UNITY, JqGridConst::WIDTH => 100));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            ProductConst::LBL_TIPO,JqGridConst::NAME => ProductConst::FLD_TIPO, JqGridConst::WIDTH => 150));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            ProductConst::LBL_DATE,JqGridConst::NAME => ProductConst::FLD_DATE, JqGridConst::WIDTH => 150));

        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            'Acao',JqGridConst::NAME => 'acao', JqGridConst::WIDTH => 80, JqGridConst::CLASSCSS => 'text-center'));

        $jqgrid->setUrl(self::URL_GET_DADOS);
        $jqgrid->setTitle('Doacoes cadastradas');

        $jqgrid->setCollapse(true);
        return $jqgrid->renderJs();
    }

    public function getGridDados(){

        /** @var ProductDao $dao */
        $dao = $this->getFromServiceLocator(ProductConst::DAO);


        /** @var \Application\Entity\User $instituicaoLogado */
        $instituicaoLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

        $qb = $dao->findProductsByUser($instituicaoLogado->getId());

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
            $temp[ProductConst::FLD_QTD] = $produto->getQuantity();
            $temp[ProductConst::FLD_UNITY] = Unidade::getUnidadeBySigla($produto->getUnity());
            $temp[ProductConst::FLD_TITLE] =  $produto->getTitle();
            $temp[ProductConst::FLD_DATE] = $data->format('d/m/Y');
            $temp[ProductConst::FLD_TIPO] = $produto->getProductType()->getDescription();
            $temp[ProductConst::FLD_DESC] = $produto->getDescription();

            $botaoEditar = new JqGridButton();
            $botaoEditar->setTitle('Editar');
            $botaoEditar->setClass('btn btn-primary btn-xs');
            $botaoEditar->setUrl('/product/editar/' . $produto->getIdProduto());
            $botaoEditar->setIcon('glyphicon glyphicon-edit');

            $botaoExcluir = new JqGridButton();
            $botaoExcluir->setTitle('Excluir');
            $botaoExcluir->setClass('btn btn-danger btn-xs');
            $botaoExcluir->setUrl('/product/excluir/' . $produto->getIdProduto());
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