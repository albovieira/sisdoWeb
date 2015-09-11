<?php

namespace Sisdo\Constants;

/**
 * Interface FormConst.
 */
interface StatusTransacaoConst
{
    const FLAG_FINALIZADO = 2;
    const LBL_FINALIZADO = 'Finalizado';

    const FLAG_PENDENTE_FINALIZACAO = 1;
    const LBL_PENDENTE_FINALIZACAO = 'Pendente Finalizacao';

    const FLAG_CANCELADO = 3;
    const LBL_CANCELADO = 'Cancelado';
}
