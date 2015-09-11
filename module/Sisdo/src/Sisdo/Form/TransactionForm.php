<?php

namespace Sisdo\Form;

use Application\Constants\GrupoConst as Grupo;
use Application\Constants\ProdutoConst;
use GerenciaEstoque\Entity\Produto;
use Sisdo\Constants\TransactionConst;
use Sisdo\Entity\Transaction;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Class TransactionForm.
 */
class TransactionForm extends Form
{

    public function __construct($url = null, $userLogado = null)
    {

        parent::__construct('transaction_form');

        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new Transaction());

        $this->setAttributes(array(
            'method' => 'post',
            'action' => $url,
        ));

        $this->add(array(
            'name' => TransactionConst::FLD_ID_TRANSACTION,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        $this->add(array(
            'name' => TransactionConst::FLD_INSTITUTION_USER,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
                'disabled' => 'disabled',

            ),
            'options' => array(
                'label' => TransactionConst::LBL_INSTITUTION_USER_ID,
            ),
        ));

        $this->add(array(
            'name' => TransactionConst::FLD_PERSON_USER,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
                'disabled' => 'disabled',

            ),
            'options' => array(
                'label' => TransactionConst::LBL_PERSON_USER_ID,
            ),
        ));

        $this->add(array(
            'name' => TransactionConst::FLD_START_DATE,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
                'disabled' => 'disabled',
            ),
            'options' => array(
                'label' => TransactionConst::LBL_START_DATE,
            ),
        ));

        $this->add(array(
            'name' => TransactionConst::FLD_END_DATE,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
                'disabled' => 'disabled',
            ),
            'options' => array(
                'label' => TransactionConst::LBL_END_DATE,
            ),
        ));

        $this->add(array(
            'name' => TransactionConst::FLD_PRODUTO,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
                'disabled' => 'disabled',
            ),
            'options' => array(
                'label' => TransactionConst::LBL_PRODUTO,
            ),
        ));

        $this->add(array(
            'name' => TransactionConst::FLD_QUANTIFY,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
                'disabled' => 'disabled',
            ),
            'options' => array(
                'label' => TransactionConst::LBL_QUANTIFY,
            ),
        ));

        $this->add(array(
            'name' => TransactionConst::FLD_STATUS,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
                'disabled' => 'disabled',
            ),
            'options' => array(
                'label' => TransactionConst::LBL_STATUS,
            ),
        ));

        $this->add(array(
            'name' => 'btn_ver_mensagens',
            'type' => 'button',
            'attributes' => array(
                'type' => 'button',
                'value' => 'Ver Mensagens',
                'id' => 'ver' . $this->getName(),
                'class' => 'btn-primary btn-white width-25 btn',
                'style' => '',
            ),
            'options' => array(
                'label' => 'Ver Mensagens',
                'glyphicon' => 'glyphicon glyphicon-floppy-disk blue',
            ),

        ));

        $this->add(array(
            'name' => 'btn_finalizar_doacao',
            'type' => 'button',
            'attributes' => array(
                'value' => 'Finalizar Doacao',
                'id' => 'salvar_' . $this->getName(),
                'class' => 'btn-primary btn-white width-25 btn',
                'style' => '',
            ),
            'options' => array(
                'label' => 'Ver Mensagens',
                'glyphicon' => 'glyphicon glyphicon-floppy-disk blue',
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
