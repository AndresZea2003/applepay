<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode;


use App\Domain\ApplePay\ApplePayLib\Enums\SignatureOIDEnum;
use App\Domain\ApplePay\ApplePayLib\Exceptions\DecodingFailedException;
use App\Domain\ApplePay\ApplePayLib\Message\Request\DecodeRequest;
use App\Domain\ApplePay\ApplePayLib\Message\Response\DecodeResponse;
use RuntimeException;

readonly class Decoder
{
    public function __construct(private DecodeRequest $request)
    {
        //
    }

    public static function make(DecodeRequest $request): self
    {
        return new self($request);
    }

    /**
     * @throws DecodingFailedException
     */
    public function decrypt(): DecodeResponse
    {
        /**
         * Step 1
         * ✅ -->  Ensure that the certificates contain the correct custom OIDs
         * 🛠 --> Ensure that the root CA is the Apple Root CA - G3. This certificate is available from
         * --> Ensure that there’s a valid X.509 chain of trust from the signature to the root CA. Specifically,
         *     ensure that the signature was created using the private key that corresponds to the leaf certificate,
         * --> Validate the token’s signature. For ECC (EC_v1), ensure that the signature is a valid Ellyptical Curve Digital
         *      Signature Algorithm (ECDSA)
         * --> Inspect the Cryptographic Message Syntax (CMS) signing time of the signature,
         *      as defined by section 11.3 of RFC 5652.
         */
        try {
            PKCS7SignatureValidator::make($this->request->paymentData->signature)->validate();
        } catch (\RuntimeException $e) {
            throw new DecodingFailedException($e->getMessage(), $e->getCode(), $e);
        }

        // TODO decode...

        return DecodeResponse::fromArray([
            'token' => $this->request->toArray()
        ]);
    }
}
