<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 05/09/2015
 * Time: 13:50
 */

namespace Application\Helper;

use Application\Constants\UsuarioConst;
use Application\Entity\User;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;


class MenuLateralHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{

    /**
     * @var ServiceLocator
     */
    private $serviceLocator;

    public function getMenuInstitution()
    {

        $helperServiceLocator = $this->getServiceLocator();
        $serviceLocator = $helperServiceLocator->getServiceLocator();

        /** @var User $userLogado */
        $userLogado = $serviceLocator->get(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();
       // $foto = $userLogado->getInstituicao()->getPicture();
        return <<<DOC
        <ul class="sidebar-menu">
                <!--<li class="header">Menu</li>-->
                <li><a href="/"><i class="glyphicon glyphicon-list-alt"></i> <span>Principal</span></a></li>
                <li><a href="/instituicao"><i class="fa fa-user"></i><span>Meus Dados</a></span></li>
                <li><a href="/produto"><i class="glyphicon glyphicon-gift"></i><span> Doacoes Produtos/Servicos<span></span></a></li>
                <li><a href="/doacao-financeira"><i class="fa fa-money"></i> <span>Doacoes em dinheiro</span></a></li>
                <li><a href="/transacao"><i class="fa fa-exchange"></i> <span>Transacoes</span></a></li>
                <li><a href="/relacionamento"><i class="fa fa-users"></i> <span>Relacionamentos</span></a></li>
                <li><a href="#"><i class="ion ion-document-text"></i> <span>Relatório</span></a></li>
        </ul>

DOC;

    }

    public function getMenuPerson()
    {
        return <<<DOC
        <ul class="sidebar-menu">
                <!--<li class="header">Menu</li>-->
                <li class="active"><a href="/"><i class="fa fa-user"></i> <span>Principal</span></a></li>
                <li><a href="/instituicao/pesquisar"><i class="fa fa-users"></i> <span>Buscar Instituicoes</span></a></li>
                <li><a href="#"><i class="glyphicon glyphicon-gift"></i> <span>Minhas doacoes</span></a></li>

        </ul>
DOC;

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