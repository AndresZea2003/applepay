<?php

namespace App\Domain\ApplePay\ApplePayLib\DTO\Decode;

use App\Domain\ApplePay\ApplePayLib\Contracts\Arrayable;

class PaymentHeader implements Arrayable
{
    public function __construct(
        public string $publicKeyHash,
        public string $ephemeralPublicKey,
        public string $transactionId
    )
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            publicKeyHash: $data['publicKeyHash'] ?? null,
            ephemeralPublicKey: $data['ephemeralPublicKey'] ?? null,
            transactionId: $data['transactionId'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'publicKeyHash' => $this->publicKeyHash,
            'ephemeralPublicKey' => $this->ephemeralPublicKey,
            'transactionId' => $this->transactionId,
        ];
    }
}
