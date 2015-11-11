<?php

/**
 * Created by PhpStorm.
 * User: albov
 * Date: 19/08/2015
 * Time: 23:35
 */

namespace Sisdo\Dao;

use Application\Custom\DaoAbstract;

class InstitutionDao extends DaoAbstract
{
    protected $entityName = 'Sisdo\\Entity\\Institution';

    public function findAll(){

        $this->getCompleteQueryBuilder()->getQuery()->getArrayResult();
    }

    public function findInstitutionByName($name){

        $qb = $this->getQueryBuilder()
            ->where($this->alias . DaoAbstract::TABLE_COLUMN_SEPARATOR . "fancyName LIKE  '%{$name}%'")
            ->orderBy($this->alias . DaoAbstract::TABLE_COLUMN_SEPARATOR . 'fancyName', 'asc');

        return $qb->getQuery()->getResult();

    }

    public function findInstitutionsByUF($uf)
    {

        $qb = $this->getQueryBuilder();
        $qb->leftJoin($this->getAlias() . '.userId', 'u')
            ->leftJoin('u.adress', 'a')
            ->where('a.uf = :uf')
            ->setParameter('uf', $uf);

        return $qb->getQuery()->getResult();
    }
}