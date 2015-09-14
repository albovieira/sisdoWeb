<?php

namespace Sisdo\Form;

use Application\Constants\FormConst;
use Sisdo\Constants\ProductConst;
use Sisdo\Constants\ProductTypeConst;
use Sisdo\Entity\Product;
use Sisdo\Entity\StatusProduto;
use Sisdo\Entity\Unidade;
use Sisdo\Service\ProductService;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Class ProdutoForm.
 */
class ProductForm extends Form
{
    /**
     * Cria o formulario para grupo.
     */
    public function __construct($url = null,\Application\Entity\User $userLogado, ProductService $service)
    {
        parent::__construct('produto_form');

        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new Product());

        $this->setAttributes(array(
            'method' => 'post',
            'action' => $url,
        ));

        $this->add(array(
            'name' => ProductConst::FLD_ID_PRODUTO,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        $this->add(array(
            'name' => ProductConst::FLD_INSTITUICAO,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
                'value' => !is_null($userLogado) ? $userLogado->getEmail() : '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        $this->add(array(
            'name' => ProductConst::FLD_DESC,
            'attributes' => array(
                'type' => 'textarea',
                'class' => '',
                'rows' => '4',
                'cols' => '4',
                'max-row' => 200
            ),
            'options' => array(
                'label' => ProductConst::LBL_DESC,
            ),
        ));

        $this->add(array(
            'name' => ProductConst::FLD_TITLE,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => ProductConst::LBL_TITLE,
            ),
        ));

        $this->add(array(
            'name' => ProductConst::FLD_STATUS,
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'empty_option' => FormConst::SELECT_OPTION_SELECIONE,
                'label' => ProductConst::LBL_STATUS,
                'value_options' => StatusProduto::getArray(),
                'disable_inarray_validator' => true,
            )
        ));

        $this->add(array(
            'name' => ProductConst::FLD_UNITY,
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'empty_option' => FormConst::SELECT_OPTION_SELECIONE,
                'label' => ProductConst::LBL_UNITY,
                'value_options' => Unidade::getArray(),
                'disable_inarray_validator' => true,
            )
        ));

        $this->add(array(
            'name' => ProductConst::FLD_TIPO,
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'empty_option' => FormConst::SELECT_OPTION_SELECIONE,
                'label' => ProductConst::LBL_TIPO,
                'value_options' => $service->createSelectFromArray(
                    $service->getProductType(),
                    ProductTypeConst::FLD_ID_PRODUCT_TYPE,
                    ProductTypeConst::FLD_DESC_PRODUCT_TYPE
                ),
                'disable_inarray_validator' => true,
            )
        ));

        $this->add(array(
            'name' => ProductConst::FLD_DATE,
            'attributes' => array(
                'type' => 'Date',
                'class' => '',
            ),
            'options' => array(
                'label' => ProductConst::LBL_DATE,
            ),
        ));

        $this->add(array(
            'name' => ProductConst::FLD_QTD,
            'attributes' => array(
                'type' => 'number',
                'class' => '',
            ),
            'options' => array(
                'label' => ProductConst::LBL_QTD,
            ),
        ));

        $this->add(array(
            'name' => 'btn_salvar',
            'type' => 'button',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Salvar',
                'id' => 'salvar_' . $this->getName(),
                'class' => 'btn-primary btn-white width-25 btn',
                'style' => '',
            ),
            'options' => array(
                'label' => 'Salvar',
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
