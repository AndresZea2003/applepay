<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode;

use App\Domain\ApplePay\ApplePayLib\Enums\SignatureOIDEnum;
use RuntimeException;

class OpenSSLServices
{
    public function __construct()
    {
        //
    }

    public static function make(): self
    {

        return new self();

    }

    public function getCertificatesFromSignature(string $signature): array
    {
        $certificates = [];
        @openssl_pkcs7_read($signature, $certificates);

       return $certificates;
    }

    /**
     * @throws RuntimeException
     */
    public function certificateContainOID(string $certificate, SignatureOIDEnum $oid): void
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
