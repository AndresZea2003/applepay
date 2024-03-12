<?php

namespace App\Domain\ApplePay\ApplePayLib\DTO\Decode;

use App\Domain\ApplePay\ApplePayLib\Contracts\Arrayable;

class PaymentMethod implements Arrayable
{
    public function __construct(
        public string $displayName,
        public string $network,
        public string $type
    )
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            displayName: $data['displayName'],
            network: $data['network'],
            type: $data['type']
        );
    }

    public function toArray(): array
    {
        return [
            'displayName' => $this->displayName,
            'network' => $this->network,
            'type' => $this->type,
        ];
    }
}
