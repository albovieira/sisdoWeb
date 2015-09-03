<?php

/**
 * Created by PhpStorm.
 * User: albov
 * Date: 19/08/2015
 * Time: 23:35
 */

namespace GerenciaEstoque\Dao;

use Application\Constants\ItemPedidoConst;
use Application\Custom\DaoAbstract;

class ItemPedidoDao extends DaoAbstract
{
    protected $entityName = 'GerenciaEstoque\\Entity\\ItemPedido';

    public function getQbItemPedido($idpedido)
    {

        $qb = $this->getCompleteQueryBuilder()
            ->where(
                $this->getAlias() . DaoAbstract::TABLE_COLUMN_SEPARATOR . ItemPedidoConst::FLD_PEDIDO .
                '=' . $idpedido
            );

        return $qb;
    }
}