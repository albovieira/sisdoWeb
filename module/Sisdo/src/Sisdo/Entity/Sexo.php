<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 08/09/2015
 * Time: 21:14
 */

namespace Sisdo\Entity;


use Sisdo\Constants\SexoConst;

class Sexo
{

    static function getArray(){
        return array(
            SexoConst::FLAG_MASCULINO => SexoConst::LBL_MASCULINO,
            SexoConst::FLAG_FEMNINO => SexoConst::LBL_FEMNINO,
        );
    }

    static function getSexoByFlag($flag){

        $sexo = Sexo::getArray();
        if (array_key_exists($flag, $sexo)) {
            return $sexo[$flag];
        } else {
            return false;
        }

    }

}