<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 08/09/2015
 * Time: 21:14
 */

namespace Application\Entity;



use Application\Constants\ProfileConst;

class Profile
{

    static function getArray(){
        return array(
            ProfileConst::FLAG_PROFILE_INSTITUTION => ProfileConst::LBL_PROFILE_INSTITUTION,
            ProfileConst::FLAG_PROFILE_PERSON => ProfileConst::LBL_PROFILE_PERSON,
        );
    }

    static function getProdutoByFlag($flag){
        $profile = Profile::getArray();

        if (array_key_exists($flag, $profile)) {
            return $profile[$flag];
        } else {
            return false;
        }

    }

}