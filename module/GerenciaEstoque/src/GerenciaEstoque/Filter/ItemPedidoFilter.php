<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 22/08/2015
 * Time: 16:35
 */

namespace GerenciaEstoque\Filter;


use Application\Constants\ItemPedidoConst;
use Zend\InputFilter\InputFilter;

class ItemPedidoFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => ItemPedidoConst::FLD_QTD,
            'required' => true,
            'filters' => array(),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => ItemPedidoConst::LBL_QTD,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => ItemPedidoConst::FLD_PRODUTO,
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => ItemPedidoConst::LBL_PRODUTO,
                    ),
                ),
            )
        ));

        $this->add(array(
            'name' => ItemPedidoConst::FLD_PEDIDO,
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => ItemPedidoConst::LBL_PEDIDO,
                    ),
                ),
            )
        ));


    }
}