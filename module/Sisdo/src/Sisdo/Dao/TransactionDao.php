<?php

/**
 * Created by PhpStorm.
 * User: albov
 * Date: 19/08/2015
 * Time: 23:35
 */

namespace Sisdo\Dao;

use Application\Custom\DaoAbstract;
use Sisdo\Constants\StatusTransacaoConst;

class TransactionDao extends DaoAbstract
{
    protected $entityName = 'Sisdo\\Entity\\Transaction';

    public function findTransactions($userId, $status){
        $qb = $this->getQueryBuilder();
        $qb->where($this->alias.DaoAbstract::TABLE_COLUMN_SEPARATOR. "institutionUser = :id")
            ->andWhere($this->alias.DaoAbstract::TABLE_COLUMN_SEPARATOR. "status = :status")
            ->setParameters(
                array(
                    'id' => $userId,
                    'status' => $status,
                )
            );

        return $qb;
    }

    public function findTransactionsByProduto($produto){
        $qb = $this->getQueryBuilder();
        $qb->where($this->alias.DaoAbstract::TABLE_COLUMN_SEPARATOR. "product = :id")
            ->andWhere($this->alias.DaoAbstract::TABLE_COLUMN_SEPARATOR. "status = :status")
            ->setParameters(
                array(
                    'id' => $produto,
                    'status' => StatusTransacaoConst::FLAG_FINALIZADO,
                )
            );
        return $qb->getQuery()->getResult();
    }

}