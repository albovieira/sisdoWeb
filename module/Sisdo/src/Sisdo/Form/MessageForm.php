<?php

namespace Sisdo\Form;

use Application\Constants\GrupoConst as Grupo;
use Application\Constants\ProdutoConst;
use GerenciaEstoque\Entity\Produto;
use Sisdo\Constants\MessageConst;
use Sisdo\Entity\Message;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Class MessageForm.
 */
class MessageForm extends Form
{
    /**
     * Cria o formulario para instituicao.
     */
    public function __construct($url = null, $userLogado = null)
    {

        parent::__construct('messageForm');

        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new Message());

        $this->setAttributes(array(
            'method' => 'post',
            'action' => $url,
        ));

        $this->add(array(
            'name' => MessageConst::FLD_MESSAGE_ID,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        $this->add(array(
            'name' => MessageConst::FLD_USER,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
            ),
            'options' => array(
                'label' => MessageConst::LBL_USER,
            ),
        ));

        $this->add(array(
            'name' => MessageConst::FLD_TRANSACAO,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
            ),
            'options' => array(
                'label' => MessageConst::LBL_TRANSACAO,
            ),
        ));

        $this->add(array(
            'name' => MessageConst::FLD_MENSAGEM,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
                'placeholder' => 'Mensagem'
            ),
            'options' => array(
                'label' => MessageConst::LBL_MENSAGEM,
            ),
        ));


        $this->add(array(
            'name' => 'btnEnviar',
            'type' => 'button',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Enviar',
                'id' => 'btnEnviar',
                'class' => 'btn-success btn-white width-25 btn',
                'style' => '',
            ),
            'options' => array(
                'label' => 'Enviar',
                'glyphicon' => 'glyphicon glyphicon-envelope blue',
            ),

        ));

        $this->add(array(
            'name' => 'btn_cancelar',
            'type' => 'button',
            'attributes' => array(
                'type' => 'reset',
                'value' => 'Cancelar',
                'id' => 'cancelar_' . $this->getName(),
                'class' => 'btn-danger btn-white margin-left-right-10px width-25 btn',
            ),
            'options' => array(
                'label' => 'Cancelar',
                'glyphicon' => 'glyphicon glyphicon-remove red',
            ),
        ));

    }
}
