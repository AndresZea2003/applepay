<?php

namespace App\Domain\ApplePay\ApplePayLib\DTO;

use App\Domain\ApplePay\ApplePayLib\DTO\Decode\PaymentData;

class PKCS7SignatureValidatorData
{
    public function __construct(
        public PaymentData $paymentData,
        public string $rootCACertificateContent
    )
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            paymentData: $data['paymentData'] ? PaymentData::fromArray($data['paymentData']) : null,
            rootCACertificateContent: $data['rootCACertificateContent'] ?? null,
        );
    }
}
