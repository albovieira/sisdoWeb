<?php

namespace Application\Form;

use Application\Constants\UsuarioConst as Usuario;
use ZfcBase\Form\ProvidesEventsForm;
use ZfcUser\Options\AuthenticationOptionsInterface;

/**
 * Class ReiniciarSenhaForm.
 */
class ReiniciarSenhaForm extends ProvidesEventsForm
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
        $this->setAttribute('id', $name);

        $this->add(array(
            'name' => Usuario::FLD_EMAIL,
            'tabindex' => $i,
            'type' => 'text',
            'attributes' => array(
                'class' => ' col-sm-10',
                'placeholder' => Usuario::LBL_EMAIL,
            ),
        ));

        ++$i;

        $this->add(array(
            'name' => Usuario::FLD_BTN_CONFIRMAR,
            'type' => 'button',
            'tabindex' => $i,
            'attributes' => array(
                'id' => Usuario::FLD_BTN_CONFIRMAR,
                'class' => 'btn-success pull-right',
            ),
            'options' => array(
                'label' => Usuario::LBL_BTN_CONFIRMAR,
            ),
        ));

        ++$i;

        $this->add(array(
            'name' => Usuario::FLD_BTN_CANCELAR,
            'type' => 'button',
            'tabindex' => $i,
            'attributes' => array(
                'onclick' => 'cancelar(this)',
                'class' => 'btn-default pull-right margin-left-right-10px',
            ),
            'options' => array(
                'label' => Usuario::LBL_BTN_CANCELAR,
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
