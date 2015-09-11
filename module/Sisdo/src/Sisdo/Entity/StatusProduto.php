<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 08/09/2015
 * Time: 21:14
 */

namespace Sisdo\Entity;


use Sisdo\Constants\StatusProdutoConst;

class StatusProduto
{

    static function getArray(){
        return array(
            StatusProdutoConst::FLAG_ATIVO => StatusProdutoConst::LBL_ATIVO,
            StatusProdutoConst::FLAG_DESATIVADO => StatusProdutoConst::LBL_DESATIVADO,
        );
    }

    static function getStatusProdutoByFlag($flag){
        $status = StatusProduto::getArray();

        if (array_key_exists($flag, $status)) {
            return $status[$flag];
        } else {
            return false;
        }

    }

}