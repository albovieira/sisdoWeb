<?php

namespace Sisdo\Form;

use Application\Constants\FormConst;
use Application\Custom\ServiceAbstract;
use Application\Entity\User;
use Sisdo\Constants\TransactionConst;
use Sisdo\Entity\ShippingMethod;
use Sisdo\Entity\Transaction;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Class TransactionUserForm.
 */
class TransactionUserForm extends Form
{

    public function __construct(ServiceAbstract $service,User $userLogado = null, $isDisabled=true,$produto)
    {

        parent::__construct('transaction_form');

        $disabled = $isDisabled ? 'disabled' : '';

        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new Transaction());

        $this->setAttributes(array(
            'method' => 'post',
           // 'action' => '/transaction/doar',
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
                'type' => 'hidden',
                'class' => '',
            ),
            'options' => array(
                'label' => TransactionConst::LBL_INSTITUTION_USER_ID,
            ),
        ));

        $this->add(array(
            'name' => TransactionConst::FLD_PERSON_USER,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
                'value' => !is_null($userLogado) ? $userLogado->getId() : '',
            ),
            'options' => array(
                'label' => TransactionConst::LBL_PERSON_USER_ID,
            ),
        ));

        $this->add(array(
            'name' => TransactionConst::FLD_START_DATE,
            'attributes' => array(
                'type' => 'Date',
                'class' => '',
                'disabled' => $disabled,
            ),
            'options' => array(
                'label' => TransactionConst::LBL_START_DATE,
            ),
        ));

        $this->add(array(
            'name' => TransactionConst::FLD_END_DATE,
            'attributes' => array(
                'type' => 'Date',
                'class' => '',
                'disabled' => $disabled,
            ),
            'options' => array(
                'label' => TransactionConst::LBL_END_DATE,
            ),
        ));


        $this->add(array(
            'name' => TransactionConst::FLD_PRODUTO,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
                'value' => $produto
            ),
            'options' => array(
                'label' => TransactionConst::LBL_PRODUTO,
            ),
        ));

        $this->add(array(
            'name' => TransactionConst::FLD_QUANTIFY,
            'attributes' => array(
                'type' => 'number',
                'class' => '',
                'disabled' => $disabled,
            ),
            'options' => array(
                'label' => TransactionConst::LBL_QUANTIFY,
            ),
        ));

        $this->add(array(
            'name' => TransactionConst::FLD_SHIPPING_METHOD,
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'empty_option' => FormConst::SELECT_OPTION_SELECIONE,
                'label' => TransactionConst::LBL_SHIPPING_METHOD,
                'value_options' => ShippingMethod::getArray(),
                'disable_inarray_validator' => true,
            )
        ));

        $this->add(array(
            'name' => TransactionConst::FLD_STATUS,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
                'disabled' => $disabled,
            ),
            'options' => array(
                'label' => TransactionConst::LBL_STATUS,
            ),
        ));

        $this->add(array(
            'name' => 'btn_salvar_doacao',
            'attributes' => array(
                'value' => 'Salvar Doacao',
                'id' => 'btn_salvar_doacao',
                'class' => 'btn-primary btn-white width-25 btn',
                'style' => '',
            ),
            'options' => array(
                'label' => 'Salvar',
                'glyphicon' => 'glyphicon glyphicon-floppy-disk blue',
            ),

        ));


    }
}
