<?php

namespace Sisdo\Form;

use Application\Constants\FormConst;
use Application\Constants\GrupoConst as Grupo;
use Application\Constants\ProdutoConst;
use GerenciaEstoque\Entity\Produto;
use Sisdo\Constants\AdressConst;
use Sisdo\Constants\InstitutionConst;
use Sisdo\Constants\StateConst;
use Sisdo\Entity\Institution;
use Sisdo\Service\AdressService;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Class InstitutionSearchForm.
 */
class InstitutionSearchForm extends Form
{
    /**
     * Cria o formulario para instituicao.
     */
    public function __construct($url = null, AdressService $service)
    {

        parent::__construct('institution_search_form');

        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new Institution());

        $this->setAttributes(array(
            'method' => 'post',
            'action' => $url,
        ));

        $this->add(array(
            'name' => InstitutionConst::FLD_FANCY_NAME,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => InstitutionConst::LBL_FANCY_NAME,
            ),
        ));



        $this->add(array(
            'name' => AdressConst::FLD_UF,
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'empty_option' => FormConst::SELECT_OPTION_SELECIONE,
                'label' => AdressConst::LBL_UF,
                'value_options' => $service->createSelectFromArray(
                    $service->getAllState(),
                    StateConst::FLD_UF,
                    StateConst::FLD_NAME
                ),
                'disable_inarray_validator' => true,
            )
        ));

        $this->add(array(
            'name' => 'btn_search',
            'type' => 'button',
            'attributes' => array(
                'type' => 'submit',
                'value' => '',
                'id' => 'btn_search',
                'class' => 'btn-primary btn-white width-25 btn',
            ),
            'options' => array(
                'label' => '',
                'glyphicon' => 'glyphicon glyphicon-search blue',
            ),

        ));

    }
}
