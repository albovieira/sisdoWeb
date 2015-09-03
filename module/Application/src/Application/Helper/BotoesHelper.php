<?php

namespace Application\Helper;

/**
 * Created by PhpStorm.
 * User: albov
 * Date: 21/08/2015
 * Time: 21:46
 */
use Zend\View\Helper\AbstractHelper;

class BotoesHelper extends AbstractHelper
{
    public function __invoke($arrBotoes)
    {
        $html = "<div class='container-botoes pull-right'>";

        foreach($arrBotoes as $botoes){

            $attrs = '';
            if (($botoes['attr'])) {

                foreach ($botoes['attr'] as $key => $atributo) {
                    $attrs .= $key . "=" . $atributo . ' ';
                }
                //var_dump($attrs);die;
            }

            if(!empty($botoes['alt']) && empty($botoes['tittle'])){
                $botoes['tittle'] = $botoes['alt'];
            }
            else{
                $botoes['alt'] = $botoes['tittle'];
            }

            $html .= "
                <a href='{$botoes['href']}' title='{$botoes['tittle']}' class='{$botoes['class-botao']}' {$attrs}>
                   <i class='{$botoes['class-icone']}'></i> {$botoes['tittle']}
                </a>
            ";
        }

        $html .= '</div>';

        return $html;
    }



}