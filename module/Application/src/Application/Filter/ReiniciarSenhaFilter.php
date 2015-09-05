<?php

namespace Application\Filter;

use Application\Constants\UsuarioConst as Usuario;
use ZfcBase\InputFilter\ProvidesEventsInputFilter;
use ZfcUser\Options\AuthenticationOptionsInterface;

/**
 * Class ReiniciarSenhaFilter.
 */
class ReiniciarSenhaFilter extends ProvidesEventsInputFilter
{
    /**
     * @param AuthenticationOptionsInterface $options
     */
    public function __construct(AuthenticationOptionsInterface $options)
    {
        $this->add(array(
            'name' => Usuario::FLD_EMAIL,
            'required' => true,
            'validators' => array(
                array(
                    'break_chain_on_failure' => true,
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => Usuario::LBL_EMAIL,
                    ),
                ),
                array(
                    'break_chain_on_failure' => true,
                    'name' => 'EmailAddress',
                    'options' => array(
                        'domain' => false,
                    ),
                ),
            ),
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
        ));
    }
}
