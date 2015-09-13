<?php

namespace Sisdo\Constants;

/**
 * Interface FormConst.
 */
interface TransactionConst
{
    const DAO = 'TransactionDao';
    const SERVICE = 'TransactionService';

    const FLD_ID_TRANSACTION = 'id';
    const LBL_ID_TRANSACTION = 'id transacao';

    const FLD_START_DATE = 'startDate';
    const LBL_START_DATE = 'Data Inicio';

    const FLD_QUANTIFY = 'quantity';
    const LBL_QUANTIFY = 'Quantidade';

    const FLD_END_DATE = 'endDate';
    const LBL_END_DATE = 'Data Fim';

    const FLD_STATUS = 'status';
    const LBL_STATUS = 'Status';

    const FLD_PRODUTO = 'product';
    const LBL_PRODUTO = 'Produto';

    const FLD_SHIPPING_METHOD = 'shippingMethod';
    const LBL_SHIPPING_METHOD = 'Metodo de Envio';

    const FLD_INSTITUTION_USER = 'institutionUser';
    const LBL_INSTITUTION_USER_ID = 'Instituicao';

    const FLD_PERSON_USER = 'personUser';
    const LBL_PERSON_USER_ID = 'Pessoa';
}
