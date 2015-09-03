<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 22/08/2015
 * Time: 16:35
 */

namespace GerenciaEstoque\Filter;


use Application\Constants\PedidoConst;
use Zend\InputFilter\InputFilter;

class PedidoFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => PedidoConst::FLD_FORNECEDOR,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => PedidoConst::LBL_FORNECEDOR,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => PedidoConst::FLD_DATA,
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => PedidoConst::LBL_DATA,
                    ),
                ),
            )
        ));


    }
}