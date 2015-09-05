<?php
/**
 * Created by PhpStorm.
 * User: BASIS-BH
 * Date: 29/06/2015
 * Time: 15:02
 */

namespace Application\Filter;


use Application\Constants\MensagensConst;
use Application\Util\Mensagens;
use ZfcBase\InputFilter\ProvidesEventsInputFilter;
use ZfcUser\Options\RegistrationOptionsInterface;

/**
 * Class EditarUsuarioFilter
 * @package Application\Filter
 */
class EditarUsuarioFilter extends ProvidesEventsInputFilter
{

    /**
     * @var array
     */
    protected $emailValidator;

    /**
     * @var array
     */
    protected $usernameValidator;

    /**
     * @var RegistrationOptionsInterface
     */
    protected $options;

    /**
     * Construtor da classe
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



        $this->getEventManager()->trigger('init', $this);
    }

    /**
     * Retorna o atributo emailValidator
     *
     * @return mixed
     */
    public function getEmailValidator()
    {
        return $this->emailValidator;
    }

    /**
     * Atribui valor a emailValidator
     *
     * @param $emailValidator
     * @return $this
     */
    public function setEmailValidator($emailValidator)
    {
        $this->emailValidator = $emailValidator;
        return $this;
    }

    /**
     * Retorna o atributo usernameValidator
     *
     * @return mixed
     */
    public function getUsernameValidator()
    {
        return $this->usernameValidator;
    }

    /**
     * Atribui valor a usernameValidator
     *
     * @param $usernameValidator
     * @return $this
     */
    public function setUsernameValidator($usernameValidator)
    {
        $this->usernameValidator = $usernameValidator;
        return $this;
    }

    /**
     * Atribui valor a options
     *
     * @param RegistrationOptionsInterface $options
     */
    public function setOptions(RegistrationOptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * Retorna o atributo options
     *
     * @return RegistrationOptionsInterface
     */
    public function getOptions()
    {
        return $this->options;
    }
}