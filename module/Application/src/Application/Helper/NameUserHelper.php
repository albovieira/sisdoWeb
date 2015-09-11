<?php

namespace Application\Helper;

/**
 * Created by PhpStorm.
 * User: albov
 * Date: 21/08/2015
 * Time: 21:46
 */
use Application\Constants\UsuarioConst;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class NameUserHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator
     */
    private $serviceLocator;

    public function getNameUser()
    {
        $helperServiceLocator = $this->getServiceLocator();
        $serviceLocator = $helperServiceLocator->getServiceLocator();

        $userLogado = $serviceLocator->get(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

        if ($userLogado->getInstituicao()) {
            $name = 'Usuario, ' . $userLogado->getInstituicao()->getFancyName();
        } else {
            $name = 'Usuario, ' . $userLogado->getPerson()->getName();
        }
        return $name;
    }


    /**
     * Set service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }


}