<?php

namespace Application\Filter;

use Application\Util\Mensagens;
use Zend\InputFilter\InputFilter;
use ZfcUser\Options\AuthenticationOptionsInterface;

/**
 * Class AlterarSenhaFilter.
 */
class AlterarSenhaFilter extends InputFilter
{
    /**
     * @param AuthenticationOptionsInterface $options
     */
    public function __construct(AuthenticationOptionsInterface $options)
    {
        $identityParams = array(
            'name' => 'identity',
            'required' => true,
            'validators' => array(),
        );

        $identityFields = $options->getAuthIdentityFields();
        if ($identityFields == array('email')) {
            $validators = array('name' => 'EmailAddress');
            array_push($identityParams['validators'], $validators);
        }

        $this->add($identityParams);

        $this->add(array(
            'name' => 'credential',
            'required' => true,
            'validators' => array(
                array(
                    'break_chain_on_failure' => true,
                    'name' => '\Application\Validator\NotEmptyValidator',
                    'options' => array(
                        'campo' => \Application\Constants\UsuarioConst::LBL_SENHA_ANTIGA,
                    ),
                ),
                array(
                    'break_chain_on_failure' => true,
                    'name' => 'Application\Validator\PasswordStrengthValidator',
                ),
                array(
                    'break_chain_on_failure' => true,
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 5,
                        'max' => 12,
                    ),
                ),
            ),
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name' => 'newCredential',
            'required' => true,
            'validators' => array(
                array(
                    'break_chain_on_failure' => true,
                    'name' => '\Application\Validator\NotEmptyValidator',
                    'options' => array(
                        'campo' => \Application\Constants\UsuarioConst::LBL_NOVA_SENHA,
                    ),
                ),
                array(
                    'break_chain_on_failure' => true,
                    'name' => 'Application\Validator\PasswordStrengthValidator',
                ),
                array(
                    'break_chain_on_failure' => true,
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 5,
                        'max' => 12,
                    ),
                ),
            ),
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name' => 'newCredentialVerify',
            'required' => true,
            'validators' => array(
                array(
                    'break_chain_on_failure' => true,
                    'name' => '\Application\Validator\NotEmptyValidator',
                    'options' => array(
                        'campo' => \Application\Constants\UsuarioConst::LBL_CONFIRM_NOVA_SENHA,
                    ),
                    array(
                        'name' => 'Application\Validator\PasswordStrengthValidator',
                    ),
                ),
                array(
                    'break_chain_on_failure' => true,
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 5,
                        'max' => 12,
                    ),
                ),
                array(
                    'break_chain_on_failure' => true,
                    'name' => 'identical',
                    'options' => array(
                        'token' => 'newCredential',
                        'messages' => array(
                            \Zend\Validator\Identical::NOT_SAME => Mensagens::getMensagem('M12'),
                        ),
                    ),
                ),
            ),
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
        ));
    }
}
