<?php

namespace App\Domain\ApplePay\ApplePayLib\DTO\Decode;

use App\Domain\ApplePay\ApplePayLib\Contracts\Arrayable;

class PaymentData implements Arrayable
{
    public function __construct(
        public string $data,
        public string $signature,
        public PaymentHeader $header,
        public string $version
    )
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            data: $data['data'] ?? null,
            signature: $data['signature'] ?? null,
            header: $data['header'] ? PaymentHeader::fromArray($data['header']) : null,
            version: $data['version'] ?? null,
        );
    }


    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'signature' => $this->signature,
            'header' => $this->header->toArray(),
            'version' => $this->version
        ];
    }
}
