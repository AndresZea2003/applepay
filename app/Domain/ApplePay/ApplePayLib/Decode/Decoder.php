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
     * @throws RuntimeException
     */
    public function decrypt(): DecodeResponse
    {
        /**
         * Step 1
         * -> Ensure that the certificates contain the correct custom OIDs: ✅
         */
        try {
            $this->validate();
        } catch (\Exception $e) {
            throw new DecodingFailedException($e->getMessage(), $e->getCode(), $e);
        }

        // TODO decode...

        return DecodeResponse::fromArray([
            'token' => $this->request->toArray()
        ]);
    }

    /**
     * @throws RuntimeException
     */
    private function validate(): void
    {
        $signature = $this->request->paymentData->signature;
        $data = "-----BEGIN CERTIFICATE-----\n$signature\n-----END CERTIFICATE-----";
        $certificates = [];
        @openssl_pkcs7_read($data, $certificates);

        $this->checkIfCertificateContainOID($certificates[0], SignatureOIDEnum::LEAF_CERTIFICATE_OID);
        $this->checkIfCertificateContainOID($certificates[1], SignatureOIDEnum::INTERMEDIATE_CERTIFICATE_OID);
    }

    /**
     * @throws RuntimeException
     */
    private function checkIfCertificateContainOID(string $certificate, SignatureOIDEnum $oid): void
    {
        $certificateResource = @openssl_x509_read($certificate);

        if(empty($certificateResource)) {
            throw new \RuntimeException("Can't load x509 certificate");
        }

        $certificateData = openssl_x509_parse($certificateResource, false);

         if (!isset($certificateData['extensions'][$oid->value])) {
             throw new \RuntimeException('Missing OID ' . $oid->value . ' from certificate');
         }
    }

}
