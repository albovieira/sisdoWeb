<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 22/08/2015
 * Time: 16:35
 */

namespace Sisdo\Filter;


use Application\Constants\ProdutoConst;
use Sisdo\Constants\ProductConst;
use Zend\InputFilter\InputFilter;

class ProdutoFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => ProductConst::FLD_DESC,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => ProductConst::LBL_DESC,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => ProductConst::FLD_TITLE,
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
                        'campo' => ProductConst::LBL_TITLE
                    ),
                ),
            )
        ));

    }
}