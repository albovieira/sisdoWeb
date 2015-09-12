<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 22/08/2015
 * Time: 16:35
 */

namespace Sisdo\Filter;


use Application\Constants\ProdutoConst;
use Sisdo\Constants\TemplateEmailConst;
use Zend\InputFilter\InputFilter;

class TemplateEmailFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => TemplateEmailConst::FLD_CONTENT,
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => TemplateEmailConst::LBL_CONTENT
                    ),
                ),
            )
        ));

        $this->add(array(
            'name' => TemplateEmailConst::FLD_DESC,
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => TemplateEmailConst::LBL_DESC
                    ),
                ),
            )
        ));

        $this->add(array(
            'name' => TemplateEmailConst::FLD_FOOTER,
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => TemplateEmailConst::LBL_FOOTER
                    ),
                ),
            )
        ));

        $this->add(array(
            'name' => TemplateEmailConst::FLD_HEADER,
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => TemplateEmailConst::LBL_HEADER
                    ),
                ),
            )
        ));

    }
}