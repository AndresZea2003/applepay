<?php

namespace App\Domain\ApplePay\ApplePayLib\DTO;

use App\Domain\ApplePay\ApplePayLib\Contracts\Arrayable;

class ApplePayAuth
{
    public function __construct(
        public string $cert,
        public string $sslKey,
        public string $sslKeyPassword,
    )
    {
        //
    }

    public static function fromArray(array $config): self
    {
        return new self(
            cert: $config['cert'] ?? null,
            sslKey: $config['sslKey'] ?? null,
            sslKeyPassword: $config['sslKeyPassword'] ?? null,
        );
    }
}
