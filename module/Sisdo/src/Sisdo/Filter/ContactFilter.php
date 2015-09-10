<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 22/08/2015
 * Time: 16:35
 */

namespace Sisdo\Filter;


use Application\Constants\ProdutoConst;
use Sisdo\Constants\ContactConst;
use Zend\InputFilter\InputFilter;

class ContactFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => ContactConst::FLD_EMAIL,
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
                        'campo' => ContactConst::LBL_EMAIL
                    ),
                ),
            )
        ));

        $this->add(array(
            'name' => ContactConst::FLD_TEL,
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
                        'campo' => ContactConst::LBL_TEL
                    ),
                ),
            )
        ));

    }
}