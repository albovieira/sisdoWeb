<?php

namespace GerenciaEstoque\Form;

use Application\Constants\FormConst;
use Application\Constants\ItemPedidoConst;
use Application\Constants\ProdutoConst;
use GerenciaEstoque\Entity\ItemPedido;
use GerenciaEstoque\Service\PedidoService;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Class ProdutoForm.
 */
class ItemPedidoForm extends Form
{
    /**
     * Cria o formulario para grupo.
     */
    public function __construct(PedidoService $service, $url = null)
    {
        parent::__construct('item_pedido_form');

        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new ItemPedido());

        $this->setAttributes(array(
            'method' => 'post',
            'action' => $url,
        ));

        $this->add(array(
            'name' => ItemPedidoConst::FLD_ID_PEDIDO,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        $this->add(array(
            'name' => ItemPedidoConst::FLD_PEDIDO,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        $this->add(array(
            'name' => ItemPedidoConst::FLD_PRODUTO,
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => '',
            ),
            'options' => array(
                'label' => ItemPedidoConst::LBL_PRODUTO,
                'empty_option' => FormConst::SELECT_OPTION_SELECIONE,
                'value_options' =>
                    $service->montarArrayNomeadoSelect(
                        $service->getProdutos(),
                        ProdutoConst::FLD_ID_PRODUTO,
                        ProdutoConst::FLD_DESC_PRODUTO

                    ),
                'disable_inarray_validator' => true,
            ),
        ));

        $this->add(array(
            'name' => ItemPedidoConst::FLD_QTD,
            'attributes' => array(
                'type' => 'number',
                'class' => '',
            ),
            'options' => array(
                'label' => ItemPedidoConst::LBL_QTD,
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
