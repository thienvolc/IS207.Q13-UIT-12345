<?php

namespace App\Domains\Payment\Constants;

class VNPayParams
{
    // Common
    public const VERSION = 'vnp_Version';
    public const COMMAND = 'vnp_Command';
    public const TMN_CODE = 'vnp_TmnCode';
    public const AMOUNT = 'vnp_Amount';
    public const CURRENCY_CODE = 'vnp_CurrCode';
    public const TXN_REF = 'vnp_TxnRef';
    public const RETURN_URL = 'vnp_ReturnUrl';
    public const ORDER_TYPE = 'vnp_OrderType';
    public const ORDER_INFO = 'vnp_OrderInfo';
    public const CREATE_DATE = 'vnp_CreateDate';
    public const EXPIRE_DATE = 'vnp_ExpireDate';
    public const LOCALE = 'vnp_Locale';
    public const IP_ADDRESS = 'vnp_IpAddr';
    public const SECURE_HASH = 'vnp_SecureHash';
    public const SECURE_HASH_TYPE = 'vnp_SecureHashType';

    // Response
    public const BANK_CODE = 'vnp_BankCode';
    public const RESPONSE_CODE = 'vnp_ResponseCode';
    public const RESPONSE_ID = 'vnp_ResponseId';
    public const TRANSACTION_NO = 'vnp_TransactionNo';
    public const TRANSACTION_STATUS = 'vnp_TransactionStatus';
    public const PAY_DATE = 'vnp_PayDate';
    public const MESSAGE = 'vnp_Message';

    // Refund specific
    public const REQUEST_ID = 'vnp_RequestId';
    public const TRANSACTION_TYPE = 'vnp_TransactionType';
    public const TRANSACTION_DATE = 'vnp_TransactionDate';
    public const CREATE_BY = 'vnp_CreateBy';
}

