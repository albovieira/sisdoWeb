<?php

namespace Sisdo\Form;

use Application\Constants\GrupoConst as Grupo;
use Application\Constants\ProdutoConst;
use GerenciaEstoque\Entity\Produto;
use Sisdo\Constants\TemplateEmailConst;
use Sisdo\Entity\Institution;
use Sisdo\Service\RelationshipService;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Class .
 */
class TemplateEmailForm extends Form
{
    /**
     * Cria o formulario para .
     */
    public function __construct($url = null, $userLogado = null, RelationshipService $service)
    {

        parent::__construct('template_email_form');


        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new Institution()); // criar a entity

        $this->setAttributes(array(
            'method' => 'post',
            'action' => $url,
        ));

        $this->add(array(
            'name' => TemplateEmailConst::FLD_ID_TEMPLATE,
            'attributes' => array(
                'type' => 'hidden',
                'class' => '',
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        $this->add(array(
            'name' => TemplateEmailConst::FLD_DESC,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => TemplateEmailConst::LBL_DESC,
            ),
        ));

        $this->add(array(
            'name' => TemplateEmailConst::FLD_HEADER,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => TemplateEmailConst::LBL_HEADER,
            ),
        ));

        $this->add(array(
            'name' => TemplateEmailConst::FLD_TIPO_TEMPLATE,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => TemplateEmailConst::LBL_TIPO_TEMPLATE,
            ),
        ));

        $this->add(array(
            'name' => TemplateEmailConst::FLD_FOOTER,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => TemplateEmailConst::LBL_FOOTER,
            ),
        ));

        $this->add(array(
            'name' => TemplateEmailConst::FLD_CONTENT,
            'attributes' => array(
                'type' => 'text',
                'class' => '',
            ),
            'options' => array(
                'label' => TemplateEmailConst::LBL_CONTENT,
            ),
        ));


        $this->add(array(
            'name' => TemplateEmailConst::FLD_TEMPLATES,
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'empty_option' => '-- Selecione --',
                'label' => TemplateEmailConst::LBL_TEMPLATES,
                'value_options' => $service->montarArrayNomeadoSelect(
                    $service->getTemplates(),
                    TemplateEmailConst::FLD_ID_TEMPLATE,
                    TemplateEmailConst::FLD_DESC
                ),
                'disable_inarray_validator' => true,
            )
        ));

        $this->add(array(
            'name' => 'btn_enviar_email',
            'type' => 'button',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Enviar',
                'id' => 'enviar' . $this->getName(),
                'class' => 'btn-primary btn-white width-25 btn',
                'style' => '',
            ),
            'options' => array(
                'label' => 'Enviar',
                'glyphicon' => 'glyphicon glyphicon-envelope blue',
            ),

        ));

        $this->add(array(
            'name' => 'btn_ver_email',
            'type' => 'button',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Ver Email',
                'id' => $this->getName(),
                'class' => 'btn-success btn-white width-25 btn',
                'style' => '',
            ),
            'options' => array(
                'label' => 'Ver Email',
                'glyphicon' => 'glyphicon glyphicon-eye-open',
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
