<?php

namespace Application\Form;

use Application\Constants\UsuarioConst as Usuario;
use ZfcBase\Form\ProvidesEventsForm;
use ZfcUser\Options\AuthenticationOptionsInterface;

/**
 * Class LoginForm.
 */
class LoginForm extends ProvidesEventsForm
{
    /**
     * @var AuthenticationOptionsInterface
     */
    protected $authOptions;

    /**
     * @param int|null|string                $name
     * @param AuthenticationOptionsInterface $options
     */
    public function __construct($name, AuthenticationOptionsInterface $options)
    {
        $this->setAuthenticationOptions($options);
        parent::__construct($name);

        $this->setAttribute('id', $name);

        $i = 0;

        $this->add(array(
            'name' => Usuario::FLD_IDENTITY,
            'tabindex' => $i,
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => ' col-sm-10',
                'placeholder' => Usuario::LBL_IDENTITY,
            ),
        ));

        ++$i;

        $this->add(array(
            'name' => Usuario::FLD_SENHA,
            'tabindex' => $i,
            'type' => '\Zend\Form\Element\Password',
            'attributes' => array(
                'class' => '',
                'placeholder' => Usuario::LBL_SENHA,
            ),
        ));

        ++$i;

        $this->add(array(
            'name' => Usuario::FLD_REDIRECT,
            'type' => '\Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => Usuario::FLD_ENTRAR,
            'type' => 'button',
            'tabindex' => $i,
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn btn-primary btn-block btn-flat',
            ),
            'options' => array(
                'label' => Usuario::LBL_ENTRAR,
                'glyphicon' => 'lock',
            ),
        ));


        $this->getEventManager()->trigger('init', $this);
    }

    /**
     * Set Authentication-related Options.
     *
     * @param AuthenticationOptionsInterface $authOptions
     *
     * @return Login
     */
    public function setAuthenticationOptions(AuthenticationOptionsInterface $authOptions)
    {
        $this->authOptions = $authOptions;

        return $this;
    }

    /**
     * Get Authentication-related Options.
     *
     * @return AuthenticationOptionsInterface
     */
    public function getAuthenticationOptions()
    {
        return $this->authOptions;
    }
}
