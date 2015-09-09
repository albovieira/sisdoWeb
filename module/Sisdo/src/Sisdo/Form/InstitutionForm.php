<?php

namespace Sisdo\Form;

use Application\Constants\GrupoConst as Grupo;
use Application\Constants\ProdutoConst;
use GerenciaEstoque\Entity\Produto;
use Sisdo\Constants\InstitutionConst;
use Sisdo\Entity\Institution;
use Sisdo\Entity\RamoInstituicao;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Class ProdutoForm.
 */
class InstitutionForm extends Form
{
    /**
     * Cria o formulario para instituicao.
     */
    public function __construct($url = null, $instituicaoLogado = null)
    {

        parent::__construct('institution_form');

        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new Institution());

        $this->setAttributes(array(
            'method' => 'post',
            'action' => $url,
        ));

        $this->add(array(
            'name' => InstitutionConst::FLD_ID_INSTITUTION,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
                'value' => !is_null($instituicaoLogado) ? $instituicaoLogado->getInstituicao()->getId() : '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        $this->add(array(
            'name' => InstitutionConst::FLD_USER_ID,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
                'value' => !is_null($instituicaoLogado) ? $instituicaoLogado->getId() : '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        $this->add(array(
            'name' => InstitutionConst::FLD_ABOUT,
            'attributes' => array(
                'type' => 'textarea',
                'class' => '',
                'rows' => '5',
                'cols' => '5',
                'max-row' => 200
            ),
            'options' => array(
                'label' => InstitutionConst::LBL_ABOUT,
            ),
        ));

        $this->add(array(
            'name' => InstitutionConst::FLD_BRANCH,
            'type' => '\Zend\Form\Element\Select',
            'options' => array(
                'label' => InstitutionConst::LBL_BRANCH,
                'empty_option' => '-- Selecione --',
                'value_options' => RamoInstituicao::getArray(),
            ),
        ));



        $this->add(array(
            'name' => InstitutionConst::FLD_CNPJ,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => InstitutionConst::LBL_CNPJ,
            ),
        ));

        $this->add(array(
            'name' => InstitutionConst::FLD_CORPORATE_NAME,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => InstitutionConst::LBL_CORPORATE_NAME,
            ),
        ));

        $this->add(array(
            'name' => InstitutionConst::FLD_FANCY_NAME,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => InstitutionConst::LBL_FANCY_NAME,
            ),
        ));

        $this->add(array(
            'name' => 'btn_salvar',
            'type' => 'button',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Salvar',
                'id' => 'salvar_' . $this->getName(),
                'class' => 'btn-primary btn-white width-25 btn',
                'style' => '',
            ),
            'options' => array(
                'label' => 'Salvar',
                'glyphicon' => 'glyphicon glyphicon-floppy-disk blue',
            ),

        ));

        $this->add(array(
            'name' => 'btn_cancelar',
            'type' => 'button',
            'attributes' => array(
                'type' => 'reset',
                'value' => 'Cancelar',
                'id' => 'cancelar_' . $this->getName(),
                'class' => 'btn-danger btn-white margin-left-right-10px width-25 btn',
            ),
            'options' => array(
                'label' => 'Cancelar',
                'glyphicon' => 'glyphicon glyphicon-remove red',
            ),
        ));

    }
}
