<?php

namespace App\Domain\ApplePay\ApplePayLib\Message\Request;

use App\Domain\ApplePay\ApplePayLib\Contracts\Arrayable;
use App\Domain\ApplePay\ApplePayLib\DTO\Decode\PaymentData;
use App\Domain\ApplePay\ApplePayLib\DTO\Decode\PaymentMethod;

class DecodeRequest implements Arrayable
{
    public function __construct(
        public PaymentData $paymentData,
    )
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            paymentData: $data['paymentData'] ? PaymentData::fromArray( $data['paymentData']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'paymentData' => $this->paymentData->toArray(),
        ];
    }
}
