<?php

namespace App\Domain\ApplePay\ApplePayLib\Enums;

enum SignatureVersion: string
{
    case ECC = 'EC_v1';
}
