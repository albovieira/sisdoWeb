<?php

namespace Application\Helper;

/**
 * Created by PhpStorm.
 * User: albov
 * Date: 21/08/2015
 * Time: 21:46
 */
use Application\Constants\UsuarioConst;
use Application\Entity\User;
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

    public function __invoke()
    {
        $helperServiceLocator = $this->getServiceLocator();
        $serviceLocator = $helperServiceLocator->getServiceLocator();

        /** @var User $userLogado */
        $userLogado = $serviceLocator->get(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();
        $data = [];
        if ($userLogado->getInstituicao()) {
            $data['profile'] = $userLogado->getProfile();
            $data['name'] = $userLogado->getInstituicao()->getFancyName();
            $data['id'] = $userLogado->getInstituicao()->getId();
            $data['picture'] = $userLogado->getInstituicao()->getPicture();
            $data['dateMember'] = $userLogado->getDate()->format('d/m/Y');
        } else {
            $data['profile'] = $userLogado->getProfile();
            $data['picture'] = $userLogado->getPerson()->getPicture();
            $data['name'] = $userLogado->getPerson()->getName();
            $data['id'] = $userLogado->getPerson()->getId();
        }
        return $data;
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