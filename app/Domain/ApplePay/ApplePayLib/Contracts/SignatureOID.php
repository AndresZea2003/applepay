<?php

namespace App\Domain\ApplePay\ApplePayLib\Contracts;

interface SignatureOID
{
    public const LEAF_CERTIFICATE_OID = '1.2.840.113635.100.6.29';
    public const INTERMEDIATE_CERTIFICATE_OID = '1.2.840.113635.100.6.2.14';
}
