<?php

namespace App\Domain\ApplePay\ApplePayLib\Message\Response;

use App\Domain\ApplePay\ApplePayLib\Contracts\Arrayable;

class DecodeResponse implements Arrayable
{
    public function __construct(
        public string $applicationPrimaryAccountNumber,
        public string $applicationExpirationDate,
        public string $currencyCode,
        public string $transactionAmount,
        public string $deviceManufacturerIdentifier,
        public string $paymentDataType,
        public string $onlinePaymentCryptogram,
        public ?string $eciIndicator,
    )
    {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            applicationPrimaryAccountNumber: $data['applicationPrimaryAccountNumber'] ?? null,
            applicationExpirationDate: $data['applicationExpirationDate'] ?? null,
            currencyCode: $data['currencyCode'] ?? null,
            transactionAmount: $data['transactionAmount'] ?? null,
            deviceManufacturerIdentifier: $data['deviceManufacturerIdentifier'] ?? null,
            paymentDataType: $data['paymentDataType'] ?? null,
            onlinePaymentCryptogram: $data['paymentData']['onlinePaymentCryptogram'] ?? null,
            eciIndicator: $data['paymentData']['eciIndicator']['eciIndicator'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'applicationPrimaryAccountNumber' => $this->applicationPrimaryAccountNumber,
            'applicationExpirationDate' => $this->applicationExpirationDate,
            'currencyCode' => $this->currencyCode,
            'transactionAmount' => $this->transactionAmount,
            'deviceManufacturerIdentifier' => $this->deviceManufacturerIdentifier,
            'paymentDataType' => $this->paymentDataType,
            'onlinePaymentCryptogram' => $this->onlinePaymentCryptogram,
            'eciIndicator' => $this->eciIndicator,
        ];
    }
}
