<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode;

use App\Domain\ApplePay\ApplePayLib\Enums\SignatureOIDEnum;
use App\Domain\ApplePay\ApplePayLib\Exceptions\CheckIntermediateCACertificateException;
use App\Domain\ApplePay\ApplePayLib\Exceptions\CheckSignatureLeafIntermediateException;
use phpseclib3\File\X509;
use RuntimeException;

class PKCS7SignatureValidator
{
    public array $certificates;
    private CertificatesServices $certificatesService;

    public function __construct(string $signature, private readonly string $rootCACertificateContent)
    {
        $data = "-----BEGIN CERTIFICATE-----\n$signature\n-----END CERTIFICATE-----";
        $this->certificatesService =  CertificatesServices::make();
        $this->certificates = $this->certificatesService->getCertificatesFromSignature($data);
    }

    public static function make(string $signature, $rootCAContent): self
    {
        return new self($signature, $rootCAContent);
    }

    /**
     * @throws RuntimeException
     * @throws CheckSignatureLeafIntermediateException
     * @throws CheckIntermediateCACertificateException
     */
    public function validate(): void
    {
        $certificates = $this->certificates;

        // 1.a. ✅ Ensure that the certificates contain the correct custom OIDs: 1.2.840.113635.100.6.29 for the leaf certificate and 1.2.840.113635.100.6.2.14 for the intermediate CA. The value for these marker OIDs doesn’t matter, only their presence.
        $this->certificatesService->certificateContainOID($certificates[0],SignatureOIDEnum::LEAF_CER_OID);
        $this->certificatesService->certificateContainOID($certificates[1], SignatureOIDEnum::INTERMEDIATE_CER_OID);

        // 1.b.✅ Ensure that the root CA is the Apple Root CA - G3. This certificate is available from apple.com/certificateauthority.
        // 1.c.✅ Ensure that there is a valid X.509 chain of trust from the signature to the root CA. Specifically, ensure that the signature was created using the private key corresponding to the leaf certificate, that the leaf certificate is signed by the intermediate CA, and that the intermediate CA is signed by the Apple Root CA - G3.
        $this->certificatesService->validateChainOfTrust($certificates, $this->rootCACertificateContent);
    }
}
