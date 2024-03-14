<?php

namespace App\Domain\ApplePay\ApplePayLib\Enums;

enum SignatureOIDEnum: string
{
    case LEAF_CERTIFICATE_OID = '1.2.840.113635.100.6.29';

    case INTERMEDIATE_CERTIFICATE_OID = '1.2.840.113635.100.6.2.14';
}
