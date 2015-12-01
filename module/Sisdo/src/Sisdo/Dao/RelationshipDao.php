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

    public function findRelationshipsByInstitutionUser($userId){
        $qb = $this->getQueryBuilder();
        $qb->where($this->alias.DaoAbstract::TABLE_COLUMN_SEPARATOR. "institutionUserId = {$userId}");

        return $qb;
    }

    public function listRelationshipPerson($userId){
        $qb = $this->getQueryBuilder();
        $qb->where($this->alias.DaoAbstract::TABLE_COLUMN_SEPARATOR. "personUserId = {$userId}");

        return $qb->getQuery()->getResult();
    }

    public function findRelationship($userId,$instituitioId){

        return
            $this->getRepository()
            ->findBy(
                array(
                    "personUserId" => $userId,
                    "institutionUserId" => $instituitioId
                )
            );
    }

}