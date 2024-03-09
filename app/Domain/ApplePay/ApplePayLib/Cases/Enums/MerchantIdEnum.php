<?php

namespace App\Domain\ApplePay\ApplePayLib\Cases\Enums;

enum MerchantIdEnum: string
{
    case UNAUTHORIZED = 'unauthorized';

    case ID_NOT_VALID = 'not_found';
}
