<?php
/**
 * Created by PhpStorm.
 * User: BASIS-BH
 * Date: 29/06/2015
 * Time: 15:02
 */

namespace Application\Filter;

use Application\Constants\MensagensConst;
use Application\Constants\UsuarioConst as Usuario;
use Application\Util\Mensagens;
use Zend\Validator\Db\NoRecordExists;
use ZfcBase\InputFilter\ProvidesEventsInputFilter;
use ZfcUser\Options\RegistrationOptionsInterface;

/**
 * Class UsuarioFilter
 * Implementa regras de validacao e filtro ao form UsuarioForm
 * @package Application\Filter
 */
class UsuarioFilter extends ProvidesEventsInputFilter
{

    /**
     * @var NoRecordExists
     */
    /**
     * @var array
     */
    protected $emailValidator;
    /**
     * @var NoRecordExists
     */
    /**
     * @var array
     */
    protected $usernameValidator;

    /**
     * @var RegistrationOptionsInterface
     */
    protected $options;
    
    /**
     * Class constructor
     *
     * @param $emailValidator
     * @param $usernameValidator
     * @param RegistrationOptionsInterface $options
     */
    public function __construct($emailValidator, $usernameValidator, RegistrationOptionsInterface $options)
    {
        $this->setOptions($options);
        $this->emailValidator = $emailValidator;
        $this->usernameValidator = $usernameValidator;

        $this->add(array(
            'name' => Usuario::FLD_EMAIL,
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => Usuario::LBL_EMAIL,
                    ),
                ),
                array(
                    'name' => 'EmailAddress'
                ),

                //$this->emailValidator
            )
        ));

        $this->add(array(
            'name' => Usuario::FLD_LOGIN,
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'campo' => Usuario::LBL_LOGIN,
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 255,
                    ),
                ),
                //$this->usernameValidator,
            ),
        ));

        $this->add(array(
            'name' => Usuario::FLD_SENHA,
            'required' => true,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'campo' => Usuario::LBL_SENHA,
                    ),
                ),

            ),
        ));

        $this->add(array(
            'name' => Usuario::FLD_SENHA_VERIFY,
            'required' => true,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'campo' => Usuario::LBL_SENHA_VERIFY,
                    ),
                ),
                array(
                    'name' => 'Identical',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'token' => Usuario::FLD_SENHA,
                        'messages' => array(
                            \Zend\Validator\Identical::NOT_SAME => 'Senhas nao coincidem',
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => Usuario::FLD_DTCADASTRO,
            'required' => false,
        ));


        $this->getEventManager()->trigger('init', $this);
    }

    /**
     * @return mixed
     */
    public function getEmailValidator()
    {
        return $this->emailValidator;
    }

    /**
     * @param $emailValidator
     * @return $this
     */
    public function setEmailValidator($emailValidator)
    {
        $this->emailValidator = $emailValidator;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsernameValidator()
    {
        return $this->usernameValidator;
    }

    /**
     * @param $usernameValidator
     * @return $this
     */
    public function setUsernameValidator($usernameValidator)
    {
        $this->usernameValidator = $usernameValidator;
        return $this;
    }

    /**
     * set options
     *
     * @param RegistrationOptionsInterface $options
     */
    public function setOptions(RegistrationOptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * get options
     *
     * @return RegistrationOptionsInterface
     */
    public function getOptions()
    {
        return $this->options;
    }
}