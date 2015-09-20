<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 05/09/2015
 * Time: 13:50
 */

namespace Application\Helper;

use Zend\View\Helper\AbstractHelper;


class MenuLateralHelper extends AbstractHelper
{
    public function getItemAtivo($link)
    {

    }

    public function getMenuInstitution()
    {

        return <<<DOC
        <div class="column col-sm-2 col-xs-1 sidebar-offcanvas" id="sidebar">

                <ul class="nav">
                    <li><a href="#" data-toggle="offcanvas" class="visible-xs text-center"><i class="glyphicon glyphicon-chevron-right"></i></a></li>
                </ul>

                <ul class="nav hidden-xs" id="lg-menu">
                    <li class="active"><a href="/"><i class="glyphicon glyphicon-list-alt"></i> Principal</a></li>
                    <li><a href="/instituicao"><i class="fa fa-user"></i> Meus Dados</a></li>
                    <li><a href="/produto"><i class="glyphicon glyphicon-gift"></i> Doacoes Produtos/Servicos</a></li>
                    <li><a href="/doacao-financeira"><i class="fa fa-money"></i> Doacoes em dinheiro</a></li>
                    <li><a href="/transacao"><i class="fa fa-exchange"></i> Transacoes</a></li>
                    <li><a href="/relacionamento"><i class="fa fa-users"></i> Relacionamentos</a></li>
                </ul>

                <!-- tiny only nav-->
                <ul class="nav visible-xs" id="xs-menu">
                    <li><a href="/" class="text-center"><i class="glyphicon glyphicon-list-alt"></i></a></li>
                    <li><a href="/instituicao" class="text-center"><i class="fa fa-user"></i></a></li>
                    <li><a href="/produto" class="text-center"><i class="glyphicon glyphicon-gift"></i></a></li>
                    <li><a href="/doacao-financeira"><i class="fa fa-money"></i></a></li>
                    <li><a href="/transacao" class="text-center"><i class="fa fa-exchange"></i></a></li>
                    <li><a href="/relacionamento" class="text-center"><i class="fa fa-users"></i></a></li>
                </ul>

        </div>
DOC;

    }

    public function getMenuPerson()
    {
        return <<<DOC
        <div class="column col-sm-2 col-xs-1 sidebar-offcanvas" id="sidebar">

                <ul class="nav">
                    <li><a href="#" data-toggle="offcanvas" class="visible-xs text-center"><i class="glyphicon glyphicon-chevron-right"></i></a></li>
                </ul>

                <ul class="nav hidden-xs" id="lg-menu">
                    <li class="active"><a href="/"><i class="fa fa-user"></i> Principal</a></li>
                    <li><a href="/instituicao/pesquisar"><i class="fa fa-users"></i> Buscar Instituicoes</a></li>
                    <li><a href="#"><i class="glyphicon glyphicon-gift"></i> Minhas doacoes</a></li>
                </ul>

                <!-- tiny only nav-->
                <ul class="nav visible-xs" id="xs-menu">
                    <li><a href="/" class="text-center"><i class="fa fa-user"></i></a></li>
                    <li><a href="/instituicao/pesquisar" class="text-center"><i class="fa fa-users"></i></a></li>
                    <li><a href="/" class="text-center"><i class="glyphicon glyphicon-gift"></i></a></li>
                </ul>

        </div>
DOC;

    }

}