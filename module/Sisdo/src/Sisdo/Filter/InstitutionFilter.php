<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 22/08/2015
 * Time: 16:35
 */

namespace Sisdo\Filter;


use Application\Constants\ProdutoConst;
use Sisdo\Constants\InstitutionConst;
use Zend\InputFilter\InputFilter;

class InstitutionFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => InstitutionConst::FLD_ABOUT,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => InstitutionConst::LBL_ABOUT,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => InstitutionConst::FLD_CORPORATE_NAME,
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
                        'campo' => InstitutionConst::LBL_CORPORATE_NAME
                    ),
                ),
            )
        ));

        $this->add(array(
            'name' => InstitutionConst::FLD_CNPJ,
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
                        'campo' => InstitutionConst::LBL_CNPJ
                    ),
                ),
            )
        ));

        $this->add(array(
            'name' => InstitutionConst::FLD_FANCY_NAME,
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
                        'campo' => InstitutionConst::LBL_FANCY_NAME
                    ),
                ),
            )
        ));

    }
}