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

    public function getMenu(){
        return <<<DOC
        <div class="sidebar">
            <ul class="nav nav-sidebar">
                <li class="active"><a href="/">Principal<span class="sr-only">(current)</span></a></li>
                <li><a href="/instituicao">Dados Instituicao</a></li>
                <li><a href="#">Relatorio</a></li>
                <li><a href="#">Transacoes</a></li>
                <li><a href="/relacionamento">Relacionamentos</a></li>
                <li><a href="#">Exportar</a></li>
            </ul>
        </div>

DOC;

    }

}