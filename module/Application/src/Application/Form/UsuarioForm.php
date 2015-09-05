<?php
/**
 * Created by PhpStorm.
 * User: BASIS-BH
 * Date: 29/06/2015
 * Time: 14:23
 */

namespace Application\Form;


use Application\Constants\FormConst;
use Application\Constants\SystemConst as Sys;
use Application\Constants\UsuarioConst as Usuario;
use Application\Entity\Cargo;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManager;
use ZfcUser\Options\RegistrationOptionsInterface;


/**
 * Class UsuarioForm
 *
 * @package Application\Form
 */
class UsuarioForm extends Form implements ObjectManagerAwareInterface
{

    /**
     * @var RegistrationOptionsInterface
     */
    protected $registrationOptions;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Class constructor
     *
     * @param int|null|string $name
     * @param RegistrationOptionsInterface $options
     * @param ServiceManager $serviceManager
     */
    function __construct($name = __CLASS__, RegistrationOptionsInterface $options, ServiceManager $serviceManager)
    {
        $this->setObjectManager($serviceManager->get(Sys::ENTITY_MANAGER));
        $this->setRegistrationOptions($options);
        parent::__construct($name);

        $entityManager = $serviceManager->get(Sys::ENTITY_MANAGER);
        $this->setHydrator(new DoctrineHydrator($entityManager, 'Application\Entity\User'));

        $this->setObject(new \Application\Entity\User());

        $defaultClass = '';


        $this->add(array(
            'name' => Usuario::FLD_SEQ_USUARIO,
            'type' => '\Zend\Form\Element\Hidden',
            'options' => array(
                'label' => Usuario::LBL_SEQ_USUARIO,
            ),
            'attributes' => array(
                'id' => Usuario::FLD_SEQ_USUARIO,
                'class' => $defaultClass
            ),
        ));

        $this->add(array(
            'name' => Usuario::FLD_LOGIN,
            'type' => '\Zend\Form\Element\Text',
            'options' => array(
                'label' => Usuario::LBL_LOGIN,
            ),
            'attributes' => array(
                'id' => Usuario::LBL_LOGIN,
                'class' => $defaultClass
            ),
        ));

        $this->add(array(
            'name' => Usuario::FLD_EMAIL,
            'type' => '\Zend\Form\Element\Text',
            'options' => array(
                'label' => Usuario::LBL_EMAIL,
            ),
            'attributes' => array(
                'id' => Usuario::FLD_EMAIL,
                'class' => $defaultClass
            ),
        ));




        $this->add(array(
            'name' => Usuario::FLD_LOGIN,
            'type' => '\Zend\Form\Element\Text',
            'options' => array(
                'label' => Usuario::LBL_LOGIN,
            ),
            'attributes' => array(
                'id' => Usuario::FLD_LOGIN,
                'class' => $defaultClass
            ),
        ));


        $this->add(array(
            'name' => Usuario::FLD_SENHA_VERIFY,
            'type' => '\Zend\Form\Element\Password',
            'options' => array(
                'label' => Usuario::LBL_SENHA_VERIFY,
            ),
            'attributes' => array(
                'id' => Usuario::FLD_SENHA_VERIFY,
                'class' => $defaultClass
            ),
        ));

        $this->add(array(
            'name' => Usuario::FLD_SENHA,
            'type' => '\Zend\Form\Element\Password',
            'options' => array(
                'label' => Usuario::LBL_SENHA,
            ),
            'attributes' => array(
                'id' => Usuario::FLD_SENHA,
                'class' => $defaultClass
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
                'glyphicon' => 'floppy-disk blue',
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
                'glyphicon' => 'remove red',
            ),
        ));

    }

    /**
     * Set Registration Options
     *
     * @param RegistrationOptionsInterface $registrationOptions
     * @return $this
     */
    public function setRegistrationOptions(RegistrationOptionsInterface $registrationOptions)
    {
        $this->registrationOptions = $registrationOptions;
        return $this;
    }

    /**
     * Get Registration Options
     *
     * @return RegistrationOptionsInterface
     */
    public function getRegistrationOptions()
    {
        return $this->registrationOptions;
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