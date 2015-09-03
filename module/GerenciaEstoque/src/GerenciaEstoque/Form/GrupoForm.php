<?php

namespace Application\Form;

use Application\Constants\FormConst as cForm;
use Application\Constants\FormConst;
use Application\Constants\GrupoConst as Grupo;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Class GrupoForm.
 */
class GrupoForm extends Form
{
    /**
     * Cria o formulario para grupo.
     */
    public function __construct($url = null)
    {
        parent::__construct('grupo_form');

        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new \Application\Entity\Grupo());

        $this->setAttributes(array(
            'method' => 'post',
            'action' => $url,
        ));

        $i = 0;

        $this->add(array(
            'name' => Grupo::FLD_SEQ_GRUPO,
            'tabindex' => $i,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        ++$i;

        $this->add(array(
            'name' => Grupo::FLD_NOME,
            'tabindex' => $i,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
                'maxlength' => 100,
            ),
            'options' => array(
                'label' => FormConst::INDICADOR_CAMPO_OBRIGATORIO.Grupo::LBL_NOME,
            ),
        ));

        ++$i;

        $this->add(array(
            'name' => Grupo::FLD_DESCRICAO,
            'type' => 'textarea',
            'attributes' => array(
                'rows' => '5',
                'cols' => '5',
            ),
            'options' => array(
                'label' => FormConst::INDICADOR_CAMPO_OBRIGATORIO.Grupo::LBL_DESCRICAO,
            ),
        ));


        ++$i;

        $this->add(array(
            'name' => Grupo::FLD_NIVEL_VISIBILIDADE,
            'tabindex' => $i,
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'empty_option' => cForm::SELECT_OPTION_SELECIONE,
                'label' => FormConst::INDICADOR_CAMPO_OBRIGATORIO.Grupo::LBL_NIVEL_VISIBILIDADE,
                'value_options' => array(
                    Grupo::VISIBILIDADE_INTERNO => Grupo::LBL_VISIBILIDADE_INTERNO,
                    Grupo::VISIBILIDADE_EXTERNO => Grupo::LBL_VISIBILIDADE_EXTERNO,
                ),
                'disable_inarray_validator' => true,
            )
        ));

        ++$i;

        $this->add(array(
            'name' => 'btn_salvar',
            'type' => 'button',
            'tabindex' => $i,
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Salvar',
                'id' => 'salvar_' . $this->getName(),
                'class' => 'btn-primary btn-white width-25 btn',
                'style' => '',
            ),
            'options' => array(
                'label' => 'Salvar',
                'glyphicon' => 'floppy-disk blue',
            ),

        ));

        ++$i;

        $this->add(array(
            'name' => 'btn_cancelar',
            'type' => 'button',
            'tabindex' => $i,
            'attributes' => array(
                'type' => 'reset',
                'value' => 'Cancelar',
                'id' => 'cancelar_' . $this->getName(),
                'class' => 'btn-danger btn-white margin-left-right-10px width-25 btn',
            ),
            'options' => array(
                'label' => 'Cancelar',
                'glyphicon' => 'remove red',
            ),
        ));

    }
}
