<?php

/**
 * Created by PhpStorm.
 * User: albov
 * Date: 19/08/2015
 * Time: 23:35
 */

namespace Sisdo\Dao;

use Application\Custom\DaoAbstract;

class TemplateEmailDao extends DaoAbstract
{
    protected $entityName = 'Sisdo\\Entity\\TemplateEmail';

    public function findTemplatesAsArray($userId)
    {
        $qb = $this->getQueryBuilder();
        $qb->where($this->alias . DaoAbstract::TABLE_COLUMN_SEPARATOR . "userId = {$userId}");

        return $qb->getQuery()->getArrayResult();
    }

}