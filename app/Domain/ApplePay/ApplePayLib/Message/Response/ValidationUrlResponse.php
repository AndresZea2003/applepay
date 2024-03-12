<?php

namespace App\Domain\ApplePay\ApplePayLib\Message\Response;

use App\Domain\ApplePay\ApplePayLib\Contracts\Arrayable;

class ValidationUrlResponse implements Arrayable
{
    public function __construct(
        public string $epochTimestamp,
        public string $expiresAt,
        public string $merchantSessionIdentifier,
        public string $nonce,
        public string $merchantIdentifier,
        public string $domainName,
        public string $displayName,
        public string $signature,
        public string $operationalAnalyticsIdentifier,
        public string $retries,
        public string $pspId,
    )
    {
        //
    }

    public static function fromArray(array $config): self
    {
        return new self(
            epochTimestamp: $config['epochTimestamp'] ?? null,
            expiresAt: $config['expiresAt'] ?? null,
            merchantSessionIdentifier: $config['merchantSessionIdentifier'] ?? null,
            nonce: $config['nonce'] ?? null,
            merchantIdentifier: $config['merchantIdentifier'] ?? null,
            domainName: $config['domainName'] ?? null,
            displayName: $config['displayName'] ?? null,
            signature: $config['signature'] ?? null,
            operationalAnalyticsIdentifier: $config['operationalAnalyticsIdentifier'] ?? null,
            retries: $config['retries'] ?? null,
            pspId: $config['pspId'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'epochTimestamp' => $this->epochTimestamp,
            'expiresAt' => $this->expiresAt,
            'merchantSessionIdentifier' => $this->merchantSessionIdentifier,
            'nonce' => $this->nonce,
            'merchantIdentifier' => $this->merchantIdentifier,
            'domainName' => $this->domainName,
            'displayName' => $this->displayName,
            'signature' => $this->signature,
            'operationalAnalyticsIdentifier' => $this->operationalAnalyticsIdentifier,
            'retries' => $this->retries,
            'pspId' => $this->pspId
        ];
    }
}
