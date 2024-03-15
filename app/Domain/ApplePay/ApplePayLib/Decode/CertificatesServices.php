<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode;

use App\Domain\ApplePay\ApplePayLib\Enums\SignatureOIDEnum;
use App\Domain\ApplePay\ApplePayLib\Exceptions\CheckIntermediateCACertificateException;
use App\Domain\ApplePay\ApplePayLib\Exceptions\CheckSignatureLeafIntermediateException;
use phpseclib3\File\X509;
use RuntimeException;

class CertificatesServices
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
        $certificateResource = $this->readX509($certificate);

        if(empty($certificateResource)) {
            throw new \RuntimeException("Can't load x509 certificate");
        }

        $certificateData = openssl_x509_parse($certificateResource, false);

        if (!isset($certificateData['extensions'][$oid->value])) {
            throw new \RuntimeException('Missing OID ' . $oid->value . ' from certificate');
        }
    }
    /**
    * @throws CheckSignatureLeafIntermediateException
    * @throws CheckIntermediateCACertificateException
    */
    public function validateChainOfTrust(array $certificates, string $rootCACertificateContent): void
    {
        $this->checkSignatureLeafIntermediate($certificates[0], $certificates[1]);
        $this->checkSignatureIntermediateRootCACertificate($certificates[1], $rootCACertificateContent);
    }

    private function readX509(string $certificate): false|\OpenSSLCertificate
    {
        return @openssl_x509_read($certificate);
    }



    /**
     * @throws CheckSignatureLeafIntermediateException
     */
    private function checkSignatureLeafIntermediate(string $leafCertificates, string $intermediateCertificate): void
    {
        try {
            $response = $this->checkChainX509($leafCertificates, $intermediateCertificate)->validateSignature();
        }catch (\Exception $e){
            throw new \RuntimeException(
                "The leaf certificate is not signed by the intermediate CA.\n",
                0,
                $e
            );
        }

        if(! $response){
            throw new CheckSignatureLeafIntermediateException();
        }

    }

    /**
     * @throws CheckIntermediateCACertificateException
     */
    private function checkSignatureIntermediateRootCACertificate(
        string $intermediateCertificate,
        string $rootCACertificateContent
    ): void
    {
        try {
            $response = $this->checkChainX509($intermediateCertificate, $rootCACertificateContent)->validateSignature();
        }catch (\Exception $e){
            throw new \RuntimeException(
                "The intermediate CA certificate is not valid or is not signed by the root CA.\n",
                0,
                $e
            );
        }

        if(! $response){
            throw new CheckIntermediateCACertificateException();
        }
    }

    private function checkChainX509(string $baseCertificate, string $signerCertificate): X509
    {
        $x509 = new X509();
        $x509->loadX509($baseCertificate);
        $x509->loadCA($signerCertificate);

        return $x509;

    }

}
