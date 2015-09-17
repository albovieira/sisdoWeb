<?php

namespace Sisdo\Form;

use Application\Constants\GrupoConst as Grupo;
use Application\Constants\ProdutoConst;
use GerenciaEstoque\Entity\Produto;
use Sisdo\Constants\AdressConst;
use Sisdo\Entity\Adress;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Class ProdutoForm.
 */
class AdressForm extends Form
{
    /**
     * Cria o formulario para instituicao.
     */
    public function __construct($url = null, $userLogado = null)
    {

        parent::__construct('adress_form');

        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new Adress());

        $this->setAttributes(array(
            'method' => 'post',
            'action' => $url,
        ));

        $this->add(array(
            'name' => AdressConst::FLD_ID_ADRESS,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        $this->add(array(
            'name' => AdressConst::FLD_USER_ID,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
                'value' => !is_null($userLogado) ? $userLogado->getId() : '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        $this->add(array(
            'name' => AdressConst::FLD_CITY,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => AdressConst::LBL_CITY,
            ),
        ));

        $this->add(array(
            'name' => AdressConst::FLD_NEIGHBORHOOD,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => AdressConst::LBL_NEIGHBORHOOD,
            ),
        ));

        $this->add(array(
            'name' => AdressConst::FLD_COUNTRY,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => AdressConst::LBL_COUNTRY,
            ),
        ));


        $this->add(array(
            'name' => AdressConst::FLD_STREET,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => AdressConst::LBL_STREET,
            ),
        ));

        $this->add(array(
            'name' => AdressConst::FLD_STREET_TYPE,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => AdressConst::LBL_STREET_TYPE,
            ),
        ));

        $this->add(array(
            'name' => AdressConst::FLD_UF,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => AdressConst::LBL_UF,
            ),
        ));

        $this->add(array(
            'name' => AdressConst::FLD_ZIP_CODE,
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => AdressConst::LBL_ZIP_CODE,
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
