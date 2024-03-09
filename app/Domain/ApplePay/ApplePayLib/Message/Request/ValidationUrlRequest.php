<?php

namespace App\Domain\ApplePay\ApplePayLib\Message\Request;

use App\Domain\ApplePay\ApplePayLib\Contracts\Arrayable;

class ValidationUrlRequest implements Arrayable
{
    public function __construct(
        public string $merchantId,
        public string $domainName,
        public string $displayName,
        public string $validationUrl,
    )
    {
        //
    }

    public static function fromArray(array $config): self
    {
        return new self(
            merchantId: $config['merchantId'] ?? null,
            domainName: $config['domainName'] ?? null,
            displayName: $config['displayName'] ?? null,
            validationUrl: $config['validationUrl'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'merchantIdentifier' => $this->merchantId,
            'domainName' => $this->domainName,
            'displayName' => $this->displayName,
        ];
    }
}
