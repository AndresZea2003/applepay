<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode;

use App\Domain\ApplePay\ApplePayLib\Enums\SignatureOIDEnum;
use RuntimeException;

class PKCS7SignatureValidator
{
    public array $certificates;
    private OpenSSLServices $openSSlService;

    public function __construct(string $signature)
    {
        $data = "-----BEGIN CERTIFICATE-----\n$signature\n-----END CERTIFICATE-----";
        $this->openSSlService =  OpenSSLServices::make();
        $this->certificates = $this->openSSlService->getCertificatesFromSignature($data);
    }

    public static function make(string $signature): self
    {
        return new self($signature);
    }

    /**
     * @throws RuntimeException
     */
    public function validate(): void
    {
        $certificates = $this->certificates;
        $this->openSSlService->certificateContainOID($certificates[0],SignatureOIDEnum::LEAF_CER_OID);
        $this->openSSlService->certificateContainOID($certificates[1], SignatureOIDEnum::INTERMEDIATE_CER_OID);
    }
}
