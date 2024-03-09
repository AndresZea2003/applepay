<?php

namespace App\Domain\ApplePay\ApplePayLib\DTO;

use App\Domain\ApplePay\ApplePayLib\Contracts\Arrayable;

class ApplePayAuth implements Arrayable
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
            cert: $config['cert'] ?? '',
            sslKey: $config['sslKey'],
            sslKeyPassword: $config['sslKeyPassword'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'cert' => $this->cert,
            'ssl_key' => $this->sslKey,
            'ssl_key_password' => $this->sslKeyPassword,
        ];
    }
}
