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

    public function findMoneyDonation($userId, $isFinalizado)
    {
        $qb = $this->getQueryBuilder();
        $qb->where($this->alias . DaoAbstract::TABLE_COLUMN_SEPARATOR . "idInstituitionUser = :id")
            ->andWhere($this->alias . DaoAbstract::TABLE_COLUMN_SEPARATOR . "status = :status");

        if($isFinalizado){
            $status = StatusTransacaoConst::FLAG_FINALIZADO;
        }
        else{
            $status = StatusTransacaoConst::FLAG_PENDENTE_FINALIZACAO;
        }
        $qb->setParameters(
            array(
                'id' => $userId,
                'status' => $status,
            )
        );
        return $qb;
    }

}