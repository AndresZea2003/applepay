<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode;

use App\Domain\ApplePay\ApplePayLib\DTO\Decode\PaymentData;
use App\Domain\ApplePay\ApplePayLib\Exceptions\SignatureException;

class EccSignatureVerifier
{
    public function __construct(public PaymentData $paymentData)
    {
        //
    }

    public static function make(PaymentData $paymentData): self
    {
         return new self($paymentData);
    }

    /**
     * @throws SignatureException
     */
    public function verifier(): void
    {
         $signedHash = hash('sha256', $this->signedData(), true);
         $asn1 = Asn1Services::make($this->paymentData->signature);

         if(!hash_equals($signedHash, $asn1->getDigestMessage())){
             throw new SignatureException('not valid digest');
         }

        $publicKey = $asn1->getLeafCertificatePublicKeyAsFormatPem();
        CertificatesServices::make()->verifySignature($asn1->getSignedAttributes(), $asn1->getSignature(), $publicKey);
    }

    private function signedData(): string
    {
        $ephemeralPublicKey =  base64_decode($this->paymentData->header->ephemeralPublicKey);
        $data = base64_decode($this->paymentData->data);
        $transactionId  =  hex2bin($this->paymentData->header->transactionId);

         return $ephemeralPublicKey . $data . $transactionId;
    }
}
