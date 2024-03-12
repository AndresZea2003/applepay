<?php

namespace App\Domain\ApplePay\ApplePayLib\Message\Request;

use App\Domain\ApplePay\ApplePayLib\Contracts\Arrayable;
use App\Domain\ApplePay\ApplePayLib\DTO\Decode\PaymentData;
use App\Domain\ApplePay\ApplePayLib\DTO\Decode\PaymentMethod;

class DecodeRequest implements Arrayable
{
    public function __construct(
        public PaymentData $paymentData,
        public PaymentMethod $paymentMethod,
        public string $transactionIdentifier
    )
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            paymentData: $data['paymentData'] ? PaymentData::fromArray( $data['paymentData']) : null,
            paymentMethod: $data['paymentMethod'] ? PaymentMethod::fromArray( $data['paymentMethod']) : null,
            transactionIdentifier: $data['transactionIdentifier'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'paymentData' => $this->paymentData->toArray(),
            'paymentMethod' => $this->paymentMethod->toArray(),
            'transactionIdentifier' => $this->transactionIdentifier,
        ];
    }
}
