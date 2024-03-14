<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode;


use App\Domain\ApplePay\ApplePayLib\Exceptions\CheckIntermediateCACertificateException;
use App\Domain\ApplePay\ApplePayLib\Exceptions\CheckSignatureLeafIntermediateException;
use App\Domain\ApplePay\ApplePayLib\Exceptions\DecodingFailedException;
use App\Domain\ApplePay\ApplePayLib\Message\Request\DecodeRequest;
use App\Domain\ApplePay\ApplePayLib\Message\Response\DecodeResponse;
use RuntimeException;

readonly class Decoder
{
    public function __construct(private DecodeRequest $request, private string $rootCACertificateContent)
    {
        //
    }

    public static function make(DecodeRequest $request, string $rootCACertificateContent): self
    {
        return new self($request, $rootCACertificateContent);
    }

    /**
     * @return DecodeResponse
     * @throws DecodingFailedException
     * @throws CheckIntermediateCACertificateException
     * @throws CheckSignatureLeafIntermediateException
     */
    public function decrypt(): DecodeResponse
    {
        /**
         * Step 1
         * --> ✅ 1a. Ensure that the certificates contain the correct custom OIDs
         *
         * --> ✅ 1b.  Ensure that the root CA is the Apple Root CA - G3. This certificate is available from
         * --> ✅ 1c. Ensure that there’s a valid X.509 chain of trust from the signature to the root CA.
         *     Specifically, ensure that the signature was created using the private key that corresponds
         *     to the leaf certificate, that the leaf certificate is signed by the intermediate CA,
         *     and that the intermediate CA is signed by the Apple Root CA - G3.
         *
         * --> 1d. Validate the token’s signature. For ECC (EC_v1), ensure that the signature is a valid Ellyptical Curve Digital
         *      Signature Algorithm (ECDSA);
         *
         * --> 1e.Inspect the Cryptographic Message Syntax (CMS) signing time of the signature,
         *      as defined by section 11.3 of RFC 5652.
         */
        $signature =  $this->request->paymentData->signature;
        try {
            PKCS7SignatureValidator::make($signature, $this->rootCACertificateContent)->validate();
        } catch (\RuntimeException $e) {
            throw new DecodingFailedException($e->getMessage(), $e->getCode(), $e);
        }

        // TODO decode...

        return DecodeResponse::fromArray([
            'token' => $this->request->toArray()
        ]);
    }
}
