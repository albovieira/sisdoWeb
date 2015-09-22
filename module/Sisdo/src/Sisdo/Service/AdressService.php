<?php

namespace Sisdo\Service;
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 20/08/2015
 * Time: 00:11
 */

use Application\Custom\ServiceAbstract;
use Sisdo\Constants\StateConst;
use Sisdo\Dao\StateDao;

class AdressService extends ServiceAbstract
{

    public function getAllState(){
        /** @var StateDao $dao */
        $dao = $this->getFromServiceLocator(StateConst::DAO);
        return $dao->getAllEstados();
    }

    public function getCityByState(){

    }

}