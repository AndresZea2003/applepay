<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode;

use App\Domain\ApplePay\ApplePayLib\Decode\ECC\ECCSignatureVerifier;
use App\Domain\ApplePay\ApplePayLib\Decode\Services\Asn1Services;
use App\Domain\ApplePay\ApplePayLib\Decode\Services\CertificatesServices;
use App\Domain\ApplePay\ApplePayLib\DTO\Decode\PaymentData;
use App\Domain\ApplePay\ApplePayLib\Enums\SignatureOIDEnum;
use App\Domain\ApplePay\ApplePayLib\Enums\SignatureVersion;
use App\Domain\ApplePay\ApplePayLib\Exceptions\CheckIntermediateCACertificateException;
use App\Domain\ApplePay\ApplePayLib\Exceptions\CheckSignatureLeafIntermediateException;
use App\Domain\ApplePay\ApplePayLib\Exceptions\SignatureException;
use RuntimeException;

class PKCS7SignatureValidator
{
    public array $certificates;
    private CertificatesServices $certificatesService;

    public function __construct(
        private readonly PaymentData $paymentData,
        private readonly string $rootCACertificateContent,
        private readonly int $signatureExpirationTime,
    )
    {
        $this->certificatesService =  CertificatesServices::make();
        $this->certificates = $this->certificatesService
            ->getCertificatesFromSignature($paymentData->signature);
    }

    public static function make(
        PaymentData $paymentData,
        string $rootCACertificateContent,
        int $signatureExpirationTime
    ): self
    {
        return new self($paymentData, $rootCACertificateContent, $signatureExpirationTime);
    }

    /**
     * @throws RuntimeException
     * @throws CheckSignatureLeafIntermediateException
     * @throws CheckIntermediateCACertificateException
     * @throws SignatureException
     */
    public function validate(): void
    {
        $certificates = $this->certificates;

        // 1.a. ✅ Ensure that the certificates contain the correct custom OIDs: 1.2.840.113635.100.6.29 for the leaf certificate and 1.2.840.113635.100.6.2.14 for the intermediate CA. The value for these marker OIDs doesn’t matter, only their presence.
        $this->certificatesService->certificateContainOID($certificates[0],SignatureOIDEnum::LEAF_CER_OID);
        $this->certificatesService->certificateContainOID($certificates[1],SignatureOIDEnum::INTERMEDIATE_CER_OID);

        // 1.b.✅ Ensure that the root CA is the Apple Root CA - G3. This certificate is available from apple.com/certificateauthority.
        // 1.c.✅ Ensure that there is a valid X.509 chain of trust from the signature to the root CA. Specifically, ensure that the signature was created using the private key corresponding to the leaf certificate, that the leaf certificate is signed by the intermediate CA, and that the intermediate CA is signed by the Apple Root CA - G3.
        $this->certificatesService->validateChainOfTrust($certificates, $this->rootCACertificateContent);

        if(! SignatureVersion::tryFrom($this->paymentData->version)){
            throw new \RuntimeException('Unsupported type ' . $this->paymentData->version);
        }

        // 1.d ✅ For ECC (EC_v1), ensure that the signature is a valid ECDSA signature (ecdsa-with-SHA256 1.2.840.10045.4.3.2) of the concatenated values of the ephemeralPublicKey, data, transactionId, and applicationData keys.
        ECCSignatureVerifier::make($this->paymentData)->verifier();

        //✅ 1.e Inspect the CMS signing time of the signature, as defined by section 11.3 of RFC 5652. If the time signature and the transaction time differ by more than a few minutes, it's possible that the token is a replay attack.
        if (! $this->validateTime($this->paymentData->signature, $this->signatureExpirationTime)) {
            throw new \RuntimeException('Signing time older than ' .  $this->signatureExpirationTime . ' seconds');
        }
    }

    private function validateTime($signature, $signatureExpirationTime): bool
    {
        $getSigningTime = Asn1Services::make($signature)->getSigningTime();
        $secondsElapsedSinceSigning = time() - strtotime($getSigningTime);

        return $secondsElapsedSinceSigning <= $signatureExpirationTime;
    }
}
