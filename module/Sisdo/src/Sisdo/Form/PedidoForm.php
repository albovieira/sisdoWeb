<?php

namespace GerenciaEstoque\Form;

use Application\Constants\FornecedorConst;
use Application\Constants\PedidoConst;
use GerenciaEstoque\Entity\Pedido;
use GerenciaEstoque\Service\PedidoService;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Class ProdutoForm.
 */
class PedidoForm extends Form
{
    /**
     * Cria o formulario para grupo.
     */
    public function __construct(PedidoService $service, $url = null)
    {
        parent::__construct('pedido_form');

        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new Pedido());

        $this->setAttributes(array(
            'method' => 'post',
            'action' => $url,
        ));

        $this->add(array(
            'name' => PedidoConst::FLD_ID_PEDIDO,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));


        $this->add(array(
            'name' => PedidoConst::FLD_FORNECEDOR,
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => '',
            ),
            'options' => array(
                'label' => PedidoConst::LBL_FORNECEDOR,
                'empty_option' => 'Selecione',
                'value_options' =>
                    $service->montarArrayNomeadoSelect(
                        $service->getFornecedores(),
                        FornecedorConst::FLD_ID_FORNEC,
                        FornecedorConst::FLD_NOME_FORNEC

                    ),
                'disable_inarray_validator' => true,
            ),
        ));

        $this->add(array(
            'name' => PedidoConst::FLD_DATA,
            'attributes' => array(
                'type' => 'Date',
                'class' => '',
            ),
            'options' => array(
                'label' => PedidoConst::LBL_DATA,
            ),
        ));

        $this->add(array(
            'name' => PedidoConst::FLD_VALOR_TOTAL,
            'attributes' => array(
                'type' => 'datetime',
                'class' => '',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => PedidoConst::LBL_VALOR_TOTAL,
            ),
        ));

        $this->add(array(
            'name' => PedidoConst::FLD_STATUS,
            'attributes' => array(
                'type' => 'datetime',
                'class' => '',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => PedidoConst::LBL_STATUS,
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
