<?php

namespace App\Domain\ApplePay\Services;

use App\Domain\ApplePay\ApplePayLib\Contracts\ApplePayApi;
use App\Domain\ApplePay\ApplePayLib\Decode\Decoder;
use App\Domain\ApplePay\ApplePayLib\Exceptions\ServicesException;
use App\Domain\ApplePay\ApplePayLib\Message\Request\DecodeRequest;
use App\Domain\ApplePay\ApplePayLib\Message\Request\ValidationUrlRequest;
use App\Domain\ApplePay\ApplePayLib\Message\Response\DecodeResponse;
use App\Domain\ApplePay\ApplePayLib\Message\Response\ValidationUrlResponse;

readonly class ApplePayServices
{
    public function __construct(private ApplePayApi $client)
    {
    }

    /**
     * @throws ServicesException
     */
    public function validationUrl(string $validationUrl): ValidationUrlResponse
    {
        $data = [
            'merchantId' => config('services.apple_pay.merchantId'),
            'domainName' => config('services.apple_pay.domainName'),
            'displayName' => config('services.apple_pay.displayName'),
            'validationUrl' => $validationUrl
        ];

        return $this->client->validationUrl(ValidationUrlRequest::fromArray($data));
    }

    public function decode(array $token): DecodeResponse
    {
        return Decoder::make(DecodeRequest::fromArray($token))->decrypt();
    }

}

