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
use Application\Util\EmailUtil\EmailUtil;
use Application\Util\JqGridButton;
use Application\Util\JqGridTable;
use Sisdo\Constants\PersonConst;
use Sisdo\Constants\RelationshipConst;
use Sisdo\Constants\TemplateEmailConst;
use Sisdo\Dao\RelationshipDao;
use Sisdo\Entity\Person;
use Sisdo\Entity\Relationship;
use Sisdo\Entity\Sexo;

class RelationshipService extends ServiceAbstract
{
    const URL_GET_DADOS = '/relacionamento/getDados';

    public function getGrid(){

        $jqgrid = new JqGridTable();
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            PersonConst::LBL_NAME,JqGridConst::NAME => PersonConst::FLD_NAME, JqGridConst::WIDTH => 150));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            PersonConst::LBL_SEX,JqGridConst::NAME => PersonConst::FLD_SEX, JqGridConst::WIDTH => 150));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            UsuarioConst::LBL_EMAIL,JqGridConst::NAME => UsuarioConst::FLD_EMAIL, JqGridConst::WIDTH => 150));

        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            'Acao',JqGridConst::NAME => 'acao', JqGridConst::WIDTH => 80, JqGridConst::CLASSCSS => 'text-center'));

        $jqgrid->setUrl(self::URL_GET_DADOS);
        $jqgrid->setTitle('Pessoas seguindo');

        return $jqgrid->renderJs();
    }

    public function getGridDados(){

        /** @var RelationshipDao $dao */
        $dao = $this->getFromServiceLocator(RelationshipConst::DAO);


        /** @var \Application\Entity\User $instituicaoLogado */
        $instituicaoLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

        $qb = $dao->findRelationshipsInstitution($instituicaoLogado->getId());

        $jqgrid = new JqGridTable();
        $jqgrid->setAlias('r');
        $jqgrid->setQuery($qb);

        //$paramsPost = $jqgrid->getParametrosFromPost();
        $rows = $jqgrid->getDatatableArray();


        $dados = [];
        foreach($rows[JqGridConst::PARAM_REGISTROS] as $row){
            /** @var Relationship $relationship */
            $relationship = $row;

            /** @var Person $pessoaRelacionada */
            $pessoaRelacionada = $relationship->getPersonUserId()->getPerson();

            $temp[PersonConst::FLD_NAME] = $pessoaRelacionada->getName();
            $temp[PersonConst::FLD_SEX] = Sexo::getSexoByFlag($pessoaRelacionada->getSex());
            $temp[UsuarioConst::FLD_EMAIL] = $pessoaRelacionada->getUserId()->getEmail();


            $botaoEditar = new JqGridButton();
            $botaoEditar->setTitle('Editar');
            $botaoEditar->setClass('btn btn-primary btn-xs');
            $botaoEditar->setUrl('/relacionamento/editar/' . $relationship->getInstitutionUserId()->getId());
            $botaoEditar->setIcon('glyphicon glyphicon-edit');

            $botaoExcluir = new JqGridButton();
            $botaoExcluir->setTitle('Excluir');
            $botaoExcluir->setClass('btn btn-danger btn-xs');
            $botaoExcluir->setUrl('/relacionamento/excluir/' . $relationship->getInstitutionUserId()->getId());
            $botaoExcluir->setIcon('glyphicon glyphicon-trash');
            //$botaoExcluir->getOnClick();


            $temp[JqGridConst::ACAO] = "<div class='agrupa-botoes'>" . $botaoEditar->render() . $botaoExcluir->render() .
                "</div>";

            $dados[] = $temp;
        }
        $rows[JqGridConst::PARAM_REGISTROS] = $dados;

        return $rows;
    }


    public function getTemplates()
    {
        /** @var \Sisdo\Dao\TemplateEmailDao $dao */
        $dao = $this->getFromServiceLocator(TemplateEmailConst::DAO);
        return array();
        //return  $dao->findTemplatesAsArray($this->getUserLogado()->getUserId());
    }

    public function incluirModeloEmail($modelo)
    {
        /** @var \Sisdo\Dao\TemplateEmailDao $dao */
        $dao = $this->getFromServiceLocator(TemplateEmailConst::DAO);
        $dao->save($modelo);

        return $modelo;
    }

    public function  enviaEmail()
    {
        $de = 'albovieira@gmail.com';
        $deNome = 'Nome remetente';

        $destinatario = 'albo.vieira@basis.com.br';
        $fromDestinatario = 'Nome destinatariao';

        $assunto = 'Teste';
        $txtEmail = 'Novoteste';

          $emailUtil = new EmailUtil($this->getServiceLocator());
        $emailUtil->setSender($deNome, $de)->sendMail($fromDestinatario, $destinatario, $assunto, $txtEmail);
    }


}