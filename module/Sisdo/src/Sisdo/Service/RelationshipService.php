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
use Sisdo\Dao\TemplateEmailDao;
use Sisdo\Entity\Person;
use Sisdo\Entity\Relationship;
use Sisdo\Entity\Sexo;
use Sisdo\Entity\TemplateEmail;

class RelationshipService extends ServiceAbstract
{
    const URL_GET_DADOS = '/relacionamento/getDados';

    public function getRelationshipByUser(){

        /** @var RelationshipDao $dao */
        $dao = $this->getFromServiceLocator(RelationshipConst::DAO);
        return $dao->listRelationship($this->getUserLogado()->getId());

    }

    public function getGrid(){

        $jqgrid = new JqGridTable();
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            PersonConst::LBL_NAME,JqGridConst::NAME => PersonConst::FLD_NAME));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            PersonConst::LBL_SEX,JqGridConst::NAME => PersonConst::FLD_SEX));
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            UsuarioConst::LBL_EMAIL,JqGridConst::NAME => UsuarioConst::FLD_EMAIL));

        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            'Acao',JqGridConst::NAME => 'acao', JqGridConst::CLASSCSS => 'text-center'));

        $jqgrid->setUrl(self::URL_GET_DADOS);
        $jqgrid->setTitle('Pessoas seguindo');
        $jqgrid->setTop('margin-top:50px');

        return $jqgrid->renderJs();
    }

    public function getGridDados(){

        /** @var RelationshipDao $dao */
        $dao = $this->getFromServiceLocator(RelationshipConst::DAO);


        /** @var \Application\Entity\User $instituicaoLogado */
        $instituicaoLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

        $qb = $dao->findRelationshipsByInstitutionUser($instituicaoLogado->getId());

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
            $botaoEditar->setTitle('Ver Mais');
            $botaoEditar->setClass('btn btn-primary btn-xs');
            $botaoEditar->setUrl('/pessoa/pagina/' . $relationship->getPersonUserId()->getId());
            $botaoEditar->setIcon('glyphicon glyphicon-eye-open');

            $temp[JqGridConst::ACAO] = "<div class='agrupa-botoes'>" . $botaoEditar->render() . "</div>";

            $dados[] = $temp;
        }
        $rows[JqGridConst::PARAM_REGISTROS] = $dados;

        return $rows;
    }


    public function getGridTemplate(){

        $jqgrid = new JqGridTable();
        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            TemplateEmailConst::LBL_DESC,JqGridConst::NAME => TemplateEmailConst::FLD_DESC, JqGridConst::WIDTH => 150));

        $jqgrid->addColunas(array(JqGridConst::LABEL  =>
            'Acao',JqGridConst::NAME => 'acao', JqGridConst::WIDTH => 80, JqGridConst::CLASSCSS => 'text-center'));

        $jqgrid->setUrl('/relacionamento/getGridTemplate');
        $jqgrid->setTitle('Templates Email');

        return $jqgrid->renderJs();
    }

    public function getGridDadosTemplate(){

        /** @var TemplateEmailDao $dao */
        $dao = $this->getFromServiceLocator(TemplateEmailConst::DAO);


        /** @var \Application\Entity\User $instituicaoLogado */
        $instituicaoLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

        $qb = $dao->findTemplatesByUser($instituicaoLogado->getId());

        $jqgrid = new JqGridTable();
        $jqgrid->setAlias('t');
        $jqgrid->setQuery($qb);

        //$paramsPost = $jqgrid->getParametrosFromPost();
        $rows = $jqgrid->getDatatableArray();

        $dados = [];
        foreach($rows[JqGridConst::PARAM_REGISTROS] as $row){
            /** @var TemplateEmail $template*/
            $template = $row;

            $temp[TemplateEmailConst::FLD_DESC] = $template->getDescription();

            $botaoEditar = new JqGridButton();
            $botaoEditar->setTitle('Editar');
            $botaoEditar->setClass('btn btn-primary btn-xs');
            $botaoEditar->setUrl('/relacionamento/editar-modelo/' . $template->getId());
            $botaoEditar->setIcon('glyphicon glyphicon-edit');

            $botaoExcluir = new JqGridButton();
            $botaoExcluir->setTitle('Excluir');
            $botaoExcluir->setClass('btn btn-danger btn-xs');
            $botaoExcluir->setUrl('/relacionamento/excluir-modelo/' . $template->getId());
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
        return  $dao->findTemplatesAsArray($this->getUserLogado()->getUserId());
    }

    public function getTemplateById($id)
    {
        /** @var \Sisdo\Dao\TemplateEmailDao $dao */
        $dao = $this->getFromServiceLocator(TemplateEmailConst::DAO);
        return  $dao->getEntity($id);
    }

    public function salvarModeloEmail(TemplateEmail $modelo)
    {
        /** @var \Sisdo\Dao\TemplateEmailDao $dao */
        $dao = $this->getFromServiceLocator(TemplateEmailConst::DAO);
        $dao->save($modelo);

        return $modelo;
    }

    public function  enviaEmail($idTemplate)
    {
        /** @var \Sisdo\Dao\TemplateEmailDao $dao */
        $dao = $this->getFromServiceLocator(TemplateEmailConst::DAO);

        /** @var \Application\Entity\User $userLogado */
        $userLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

        /** @var TemplateEmail $template */
        $template = $dao->getEntity($idTemplate);

        //$de = $userLogado->getEmail();
        $de = 'albovieira@gmail.com';
        $deNome = $userLogado->getInstituicao()->getFancyName();

        $destinatario = 'albo.vieira@basis.com.br';
        $fromDestinatario = 'Nome destinatariao';

        $assunto = 'Doacao- Criar campo assunto';
        $txtEmail = <<<DOC
        {$template->getHeader()} <br>
        {$template->getContent()}  <br><br>
        {$template->getFooter()} <br>
DOC;

        $emailUtil = new EmailUtil($this->getServiceLocator());
        $emailUtil->setSender($deNome, $de)->sendMail($fromDestinatario, $destinatario, $assunto, $txtEmail);

        return true;
    }


}