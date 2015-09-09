<?php

namespace Sisdo\Form;

use Application\Constants\GrupoConst as Grupo;
use Application\Constants\ProdutoConst;
use GerenciaEstoque\Entity\Produto;
use Sisdo\Constants\ContactConst;
use Sisdo\Entity\Institution;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Class ProdutoForm.
 */
class ContactForm extends Form
{
    /**
     * Cria o formulario para instituicao.
     */
    public function __construct($url = null, $userLogado)
    {

        parent::__construct('contact_form');

        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new Institution());

        $this->setAttributes(array(
            'method' => 'post',
            'action' => $url,
        ));

        $this->add(array(
            'name' => ContactConst::FLD_ID_CONTACT,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        $this->add(array(
            'name' => ContactConst::FLD_USER_ID,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
                'value' => $userLogado->getId(),
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        $this->add(array(
            'name' => ContactConst::FLD_EMAIL,
            'attributes' => array(
                'type' => 'email',
                'class' => '',
                'value' => $userLogado->getEmail(),
            ),
            'options' => array(
                'label' => ContactConst::LBL_EMAIL,
            ),
        ));

        $this->add(array(
            'name' => ContactConst::FLD_TEL,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => ContactConst::LBL_TEL,
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
