<?php

namespace Application\Filter;

use Application\Constants\UsuarioConst as Usuario;
use ZfcBase\InputFilter\ProvidesEventsInputFilter;
use ZfcUser\Options\AuthenticationOptionsInterface;

/**
 * Class LoginFilter.
 */
class LoginFilter extends ProvidesEventsInputFilter
{
    /**
     * @param AuthenticationOptionsInterface $options
     */
    public function __construct(AuthenticationOptionsInterface $options)
    {
        $this->add(array(
            'name' => Usuario::FLD_IDENTITY,
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => Usuario::LBL_IDENTITY,
                    ),
                ),
            ),
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name' => Usuario::FLD_SENHA,
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => Usuario::LBL_SENHA,
                    ),
                ),
                 array(
                     'name' => 'StringLength',
                     'options' => array(
                         'min' => 6,
                     ),
                 ),
            ),
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }
}
