<?php

namespace App\Domain\ApplePay\ApplePayLib\Message\Response;

use App\Domain\ApplePay\ApplePayLib\Contracts\Arrayable;

class DecodeResponse implements Arrayable
{
    public function __construct(
        public array $token
    )
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            token: $data['token']
        );
    }

    public function toArray(): array
    {
        return [
            'token' => $this->token
        ];
    }
}
