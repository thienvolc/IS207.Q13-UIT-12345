<?php

namespace App\Domains\Payment\Constants;

class PayOSParams
{
    // Request params
    public const ORDER_CODE = 'orderCode';
    public const AMOUNT = 'amount';
    public const DESCRIPTION = 'description';
    public const ITEMS = 'items';
    public const RETURN_URL = 'returnUrl';
    public const CANCEL_URL = 'cancelUrl';
    public const BUYER_NAME = 'buyerName';
    public const BUYER_EMAIL = 'buyerEmail';
    public const BUYER_PHONE = 'buyerPhone';
    public const BUYER_ADDRESS = 'buyerAddress';
    public const EXPIRE_AT = 'expiredAt';

    // Item params
    public const ITEM_NAME = 'name';
    public const ITEM_QUANTITY = 'quantity';
    public const ITEM_PRICE = 'price';

    // Response params
    public const CHECKOUT_URL = 'checkoutUrl';
    public const QR_CODE = 'qrCode';
    public const STATUS = 'status';
    public const CODE = 'code';
    public const DESC = 'desc';
    public const DATA = 'data';

    // Webhook data
    public const ACCOUNT_NUMBER = 'accountNumber';
    public const REFERENCE = 'reference';
    public const TRANSACTION_DATE_TIME = 'transactionDateTime';
    public const PAYMENT_LINK_ID = 'paymentLinkId';
}
