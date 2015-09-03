<?php

namespace GerenciaEstoque\Form;

use Application\Constants\FornecedorConst;
use GerenciaEstoque\Entity\Fornecedor;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Class ProdutoForm.
 */
class FornecedorForm extends Form
{
    /**
     * Cria o formulario para grupo.
     */
    public function __construct($url = null)
    {
        parent::__construct('fornecedor_form');

        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new Fornecedor());

        $this->setAttributes(array(
            'method' => 'post',
            'action' => $url,
        ));

        $this->add(array(
            'name' => FornecedorConst::FLD_ID_FORNEC,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        $this->add(array(
            'name' => FornecedorConst::FLD_NOME_FORNEC,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => FornecedorConst::LBL_NOME_FORNEC,
            ),
        ));

        $this->add(array(
            'name' => FornecedorConst::FLD_CNPJ,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
                'id' => FornecedorConst::FLD_CNPJ
            ),
            'options' => array(
                'label' => FornecedorConst::LBL_CNPJ,
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
