<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 08/09/2015
 * Time: 21:14
 */

namespace Sisdo\Entity;


use Sisdo\Constants\UnidadeConst;

class Unidade
{

    static function getArray(){
        return array(
            UnidadeConst::SIGLA_UNITARIO => UnidadeConst::LBL_UNITARIO,
            UnidadeConst::SIGLA_DEZENA => UnidadeConst::LBL_DEZENA,
            UnidadeConst::SIGLA_CENTENA => UnidadeConst::LBL_CENTENA,
            UnidadeConst::SIGLA_GRAMA => UnidadeConst::LBL_GRAMA,
            UnidadeConst::SIGLA_KILO => UnidadeConst::LBL_KILO,
        );
    }

    static function getUnidadeBySigla($sigla){
        $unidades = Unidade::getArray();

        if (array_key_exists($sigla, $unidades)) {
            return $unidades[$sigla];
        } else {
            return false;
        }

    }

}