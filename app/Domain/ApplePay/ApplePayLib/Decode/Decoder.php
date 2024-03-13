<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode;

use App\Domain\ApplePay\ApplePayLib\Message\Request\DecodeRequest;
use App\Domain\ApplePay\ApplePayLib\Message\Response\DecodeResponse;

class Decoder
{
    public static function make(DecodeRequest $request): DecodeResponse
    {
        // TODO decode...
 /*       Get AppleRootCA-G3.pem:

        Download AppleRootCA-G3.cer
        Run command: openssl x509 -inform der -in storage/app/AppleRootCA-G3.cer -out AppleRootCA-G3.pem*/

        $signature = base64_decode($request->paymentData->signature);

        if(empty($signature)) {
            throw new \RuntimeException('Signature is not a valid base64 value');
        }

        $certificatePath = storage_path('app/certificados/AppleRootCA-G3.pem');

        $getCertificatesCommand = ['openssl', 'pkcs7', '-inform', 'DER', '-in', $certificatePath, '-print_certs'];


        return DecodeResponse::fromArray([
            'token' => $request->toArray()
        ]);

    }

}
