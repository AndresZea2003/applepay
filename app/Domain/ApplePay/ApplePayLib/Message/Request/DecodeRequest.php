<?php

namespace App\Domain\ApplePay\ApplePayLib\Message\Request;

use App\Domain\ApplePay\ApplePayLib\Contracts\Arrayable;
use App\Domain\ApplePay\ApplePayLib\DTO\Decode\PaymentData;
use App\Domain\ApplePay\ApplePayLib\DTO\Decode\PaymentMethod;

class DecodeRequest implements Arrayable
{
    public function __construct(
        public PaymentData $paymentData,
        public string $rootCACertificateContent,
        public int $expirationTime,
        public string $privateKey,
        public string $merchantId
    )
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            paymentData: $data['paymentData'] ? PaymentData::fromArray($data['paymentData']) : null,
            rootCACertificateContent: $data['rootCACertificateContent'],
            expirationTime: $data['expirationTime'],
            privateKey: $data['privateKey'],
            merchantId: $data['merchantId'],
        );
    }

    public function toArray(): array
    {
        return [
            'paymentData' => $this->paymentData->toArray(),
            'rootCACertificateContent' => $this->rootCACertificateContent,
            'expirationTime' => $this->expirationTime,
            'privateKey' => $this->privateKey,
            'merchantId' => $this->merchantId
        ];
    }
}
