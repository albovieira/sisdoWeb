<?php

/**
 * Created by PhpStorm.
 * User: albov
 * Date: 19/08/2015
 * Time: 23:35
 */

namespace Sisdo\Dao;

use Application\Custom\DaoAbstract;

class ProductTypeDao extends DaoAbstract
{
    protected $entityName = 'Sisdo\\Entity\\ProductType';

    public function findProductTypeAsArray()
    {
        $qb = $this->getQueryBuilder();
        return $qb->getQuery()->getArrayResult();
    }

}