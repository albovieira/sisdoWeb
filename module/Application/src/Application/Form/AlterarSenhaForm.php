<?php

namespace Application\Form;

use Application\Constants\UsuarioConst as Usuario;
use ZfcBase\Form\ProvidesEventsForm;
use ZfcUser\Options\AuthenticationOptionsInterface;

/**
 * Class AlterarSenhaForm.
 */
class AlterarSenhaForm extends ProvidesEventsForm
{
    /**
     * @var AuthenticationOptionsInterface
     */
    protected $authOptions;

    /**
     * @param int|null|string $name
     * @param AuthenticationOptionsInterface $options
     */
    public function __construct($name, AuthenticationOptionsInterface $options)
    {
        $this->setAuthenticationOptions($options);
        parent::__construct($name);

        $i = 0;

        $this->add(array(
            'name' => 'identity',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'credential',
            'options' => array(
                'label' => Usuario::LBL_SENHA_ANTIGA
            ),
            'attributes' => array(
                'type' => 'password',
            ),
        ));

        $this->add(array(
            'name' => 'newCredential',
            'options' => array(
                'label' => Usuario::LBL_NOVA_SENHA,
            ),
            'attributes' => array(
                'type' => 'password',
            ),
        ));

        $this->add(array(
            'name' => 'newCredentialVerify',
            'options' => array(
                'label' => Usuario::LBL_CONFIRM_NOVA_SENHA,
            ),
            'attributes' => array(
                'type' => 'password',
            ),
        ));

        $this->add(array(
            'name' => Usuario::FLD_BTN_CONFIRMAR,
            'type' => 'button',
            'tabindex' => $i,
            'attributes' => array(
                'id' => Usuario::FLD_BTN_CONFIRMAR,
                'class' => 'btn-primary pull-right width-35 btn-sm',
            ),
            'options' => array(
                'label' => Usuario::LBL_BTN_CONFIRMAR,
                'glyphicon' => 'ok',
            ),

        ));

        ++$i;

        $this->add(array(
            'name' => Usuario::FLD_BTN_CANCELAR,
            'type' => 'button',
            'tabindex' => $i,
            'attributes' => array(
                'data-href' => 'login',
                'onclick' => 'cancelar(this)',
                'class' => 'btn btn-default pull-right margin-left-right-10px width-35 btn-sm',
            ),
            'options' => array(
                'label' => Usuario::LBL_BTN_CANCELAR,
                'glyphicon' => 'remove',
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
