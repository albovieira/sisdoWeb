<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 22/08/2015
 * Time: 16:35
 */

namespace Sisdo\Filter;


use Application\Constants\ProdutoConst;
use Sisdo\Constants\AdressConst;
use Zend\InputFilter\InputFilter;

class AdressFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => AdressConst::FLD_CITY,
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
                        'campo' => AdressConst::LBL_CITY
                    ),
                ),
            )
        ));

        $this->add(array(
            'name' => AdressConst::FLD_COUNTRY,
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
                        'campo' => AdressConst::LBL_COUNTRY
                    ),
                ),
            )
        ));

        $this->add(array(
            'name' => AdressConst::FLD_NEIGHBORHOOD,
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
                        'campo' => AdressConst::LBL_NEIGHBORHOOD
                    ),
                ),
            )
        ));

        $this->add(array(
            'name' => AdressConst::FLD_STREET,
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
                        'campo' => AdressConst::LBL_STREET
                    ),
                ),
            )
        ));

        $this->add(array(
            'name' => AdressConst::FLD_ZIP_CODE,
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
                        'campo' => AdressConst::LBL_ZIP_CODE
                    ),
                ),
            )
        ));

        $this->add(array(
            'name' => AdressConst::FLD_UF,
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
                        'campo' => AdressConst::LBL_UF
                    ),
                ),
            )
        ));
    }
}