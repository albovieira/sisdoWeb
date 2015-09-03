<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 22/08/2015
 * Time: 16:35
 */

namespace GerenciaEstoque\Filter;


use Application\Constants\FornecedorConst;
use Zend\InputFilter\InputFilter;

class FornecedorFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => FornecedorConst::FLD_NOME_FORNEC,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => FornecedorConst::LBL_NOME_FORNEC,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => FornecedorConst::FLD_CNPJ,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'Digits'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => FornecedorConst::LBL_CNPJ
                    ),
                ),
            )
        ));

    }
}