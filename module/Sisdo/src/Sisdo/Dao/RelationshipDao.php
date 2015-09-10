<?php

/**
 * Created by PhpStorm.
 * User: albov
 * Date: 19/08/2015
 * Time: 23:35
 */

namespace Sisdo\Dao;

use Application\Custom\DaoAbstract;

class RelationshipDao extends DaoAbstract
{
    protected $entityName = 'Sisdo\\Entity\\Relationship';

    public function findRelationshipsInstitution($userId){
        $qb = $this->getQueryBuilder();
        $qb->where($this->alias.DaoAbstract::TABLE_COLUMN_SEPARATOR. "institutionUserId = {$userId}");
            //->setParameter('id',);

        return $qb;
    }

}