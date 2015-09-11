<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 08/09/2015
 * Time: 21:14
 */

namespace Sisdo\Entity;


use Sisdo\Constants\StatusTransacaoConst;

class StatusTransacao
{

    static function getArray(){
        return array(
            StatusTransacaoConst::FLAG_PENDENTE_FINALIZACAO => StatusTransacaoConst::LBL_PENDENTE_FINALIZACAO,
            StatusTransacaoConst::FLAG_FINALIZADO => StatusTransacaoConst::LBL_FINALIZADO,
            StatusTransacaoConst::FLAG_CANCELADO => StatusTransacaoConst::LBL_CANCELADO,
        );
    }

    static function getStatusByFlag($flag){
        $status = StatusTransacao::getArray();

        if (array_key_exists($flag, $status)) {
            return $status[$flag];
        } else {
            return false;
        }

    }

}