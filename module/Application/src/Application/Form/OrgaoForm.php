<?php

namespace Application\Form;

use Application\Constants\FormConst as cForm;
use Application\Constants\FormConst;
use Application\Constants\OrgaoConst as Orgao;
use Application\Constants\SystemConst as Sys;
use Application\Entity\Regiao;
use Application\Filter\OrgaoFilter;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManager;

/**
 * Class CustodiadoForm.
 */
class OrgaoForm extends Form implements ObjectManagerAwareInterface
{

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Cria o formuláro para Orgão
     *
     * @param string $name
     * @param ServiceManager $serviceManager
     */
    public function __construct($name, ServiceManager $serviceManager)
    {
        parent::__construct($name);

        $entityManager = $serviceManager->get(Sys::ENTITY_MANAGER);
        $this->setObjectManager($entityManager);
        $this->setHydrator(new DoctrineHydrator($entityManager, 'Application\Entity\Orgao'));
        $this->bind(new \Application\Entity\Orgao());

        $i = 0;
        $defaultClass = '';

        $this->add(array(
            'name' => Orgao::FLD_SEQ_ORGAO,
            'type' => '\Zend\Form\Element\Hidden',
            'tabindex' => $i++,
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'id' => Orgao::FLD_SEQ_ORGAO,
                'class' => $defaultClass
            ),
        ));

        $this->add(array(
            'type' => 'DoctrineORMModule\Form\Element\EntitySelect',
            'name' => Orgao::FLD_TIPO_ORGAO,
            'tabindex' => $i++,
            'attributes' => array(
                'id' => Orgao::FLD_TIPO_ORGAO,
                'class' => $defaultClass
            ),
            'options' => array(
                'label' =>FormConst::INDICADOR_CAMPO_OBRIGATORIO.Orgao::LBL_TIPO_ORGAO,
                'empty_option' => cForm::SELECT_OPTION_SELECIONE,
                'object_manager' => $this->getObjectManager(),
                'target_class' => 'Application\Entity\TipoOrgao',
                'property' => 'nomeTipoOrgao',
                'find_method' => array(
                    'name' => 'findAll',
                ),
            ),
        ));

        $this->add(array(
            'name' => Orgao::FLD_NOME,
            'type' => '\Zend\Form\Element\Text',
            'tabindex' => $i++,
            'options' => array(
                'label' => FormConst::INDICADOR_CAMPO_OBRIGATORIO.Orgao::LBL_NOME,
            ),
            'attributes' => array(
                'id' => Orgao::FLD_NOME,
                'class' => $defaultClass
            ),
        ));

        $this->add(array(
            'name' => Orgao::FLD_REGIAO,
            'type' => '\Zend\Form\Element\Select',
            'tabindex' => $i++,
            'options' => array(
                'label' => FormConst::INDICADOR_CAMPO_OBRIGATORIO.Orgao::LBL_REGIAO,
                'empty_option' => cForm::SELECT_OPTION_SELECIONE,
                'value_options' => Regiao::getArray(),
            ),
            'attributes' => array(
                'id' => Orgao::FLD_REGIAO,
                'class' => $defaultClass
            ),
        ));

        $this->add(array(
            'type' => 'DoctrineORMModule\Form\Element\EntitySelect',
            'name' => Orgao::FLD_CIDADE,
            'tabindex' => $i++,
            'attributes' => array(
                'id' => Orgao::FLD_CIDADE,
                'class' => $defaultClass
            ),
            'options' => array(
                'label' => FormConst::INDICADOR_CAMPO_OBRIGATORIO.Orgao::LBL_CIDADE,
                'empty_option' => cForm::SELECT_OPTION_SELECIONE,
                'object_manager' => $this->getObjectManager(),
                'target_class' => 'Application\Entity\Cidade',
                'property' => 'nomeCidade',
                'find_method' => array(
                    'name' => 'findAll',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'btn_salvar',
            'type' => 'button',
            'tabindex' => $i++,
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Salvar',
                'id' => 'salvar_' . $this->getName(),
                'class' => 'btn-primary btn-white width-25 btn',
                'style' => '',
            ),
            'options' => array(
                'label' => 'Salvar',
                'glyphicon' => 'floppy-disk blue',
            ),

        ));

        $this->add(array(
            'name' => 'btn_cancelar',
            'type' => 'button',
            'tabindex' => $i++,
            'attributes' => array(
                'type' => 'reset',
                'value' => 'Cancelar',
                'id' => 'cancelar_' . $this->getName(),
                'class' => 'btn-danger btn-white margin-left-right-10px width-25 btn',
            ),
            'options' => array(
                'label' => 'Cancelar',
                'glyphicon' => 'remove red',
            ),
        ));

        $this->setUseInputFilterDefaults(false);
        $this->setInputFilter(new OrgaoFilter());

    }


    /**
     * @param ObjectManager $objectManager
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return mixed
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
}
