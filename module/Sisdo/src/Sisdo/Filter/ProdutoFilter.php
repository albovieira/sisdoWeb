<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 22/08/2015
 * Time: 16:35
 */

namespace GerenciaEstoque\Filter;


use Application\Constants\ProdutoConst;
use Zend\InputFilter\InputFilter;

class ProdutoFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => ProdutoConst::FLD_DESC_PRODUTO,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => ProdutoConst::LBL_DESC_PRODUTO,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => ProdutoConst::FLD_VAL_UNITARIO,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                //array('name' => 'Digits'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => ProdutoConst::LBL_VAL_UNITARIO
                    ),
                ),
            )
        ));

    }
}