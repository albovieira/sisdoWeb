<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 08/09/2015
 * Time: 21:14
 */

namespace Sisdo\Entity;



use Sisdo\Constants\ShippingMethodConst;

class ShippingMethod
{

    static function getArray(){
        return array(
            ShippingMethodConst::FLAG_CORREIO=> ShippingMethodConst::LBL_CORREIO,
            ShippingMethodConst::FLAG_ENTREGA_PESSOALMENTE=> ShippingMethodConst::LBL_ENTREGA_PESSOALMENTE,
            ShippingMethodConst::FLAG_NAO_SE_APLICA=> ShippingMethodConst::LBL_NAO_SE_APLICA,
        );
    }

    static function getShippingMethod($flag){
        $shipping = ShippingMethod::getArray();

        if (array_key_exists($flag, $shipping)) {
            return $shipping[$flag];
        } else {
            return false;
        }

    }

}