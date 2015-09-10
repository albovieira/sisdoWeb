<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 08/09/2015
 * Time: 21:14
 */

namespace Sisdo\Entity;


use Sisdo\Constants\RamoInstituicaoConst;

class RamoInstituicao
{

    static function getArray(){
        return array(
            RamoInstituicaoConst::FLD_APOIO_USUARIOS_ALCOOLATRAS => RamoInstituicaoConst::LBL_APOIO_USUARIOS_ALCOOLATRAS,
            RamoInstituicaoConst::FLD_APOIO_USUARIOS_DROGAS => RamoInstituicaoConst::LBL_APOIO_USUARIOS_DROGAS,
            RamoInstituicaoConst::FLD_ASILOS => RamoInstituicaoConst::LBL_ASILOS,
            RamoInstituicaoConst::FLD_CRIANCAS_CARENTES => RamoInstituicaoConst::LBL_CRIANCAS_CARENTES,
            RamoInstituicaoConst::FLD_CRIANCAS_DEFICIENTE_MENTAL => RamoInstituicaoConst::LBL_CRIANCAS_DEFICIENTE_MENTAL,
        );
    }

    static function getRamoById($id){
        $ramos = RamoInstituicao::getArray();

        if (array_key_exists($id, $ramos)) {
            return $ramos[$id];
        } else {
            return false;
        }

    }

}