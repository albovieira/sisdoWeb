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

class MoneyDonationDao extends DaoAbstract
{
    protected $entityName = 'Sisdo\\Entity\\MoneyDonation';

    public function findMoneyDonationPendente($userId)
    {
        $qb = $this->getQueryBuilder();
        $qb->where($this->alias . DaoAbstract::TABLE_COLUMN_SEPARATOR . "idInstituitionUser = :id")
            ->andWhere($this->alias . DaoAbstract::TABLE_COLUMN_SEPARATOR . "status = :status")
            ->setParameters(
                array(
                    'id' => $userId,
                    'status' => StatusTransacaoConst::FLAG_PENDENTE_FINALIZACAO,
                )
            );

        return $qb;
    }

}