<?php

namespace App\Domain\ApplePay\ApplePayLib;


use App\Domain\ApplePay\ApplePayLib\Contracts\ApplePayApi;
use App\Domain\ApplePay\ApplePayLib\Entities\Settings;
use App\Domain\ApplePay\ApplePayLib\Exceptions\InvalidSettingsException;
use App\Domain\ApplePay\ApplePayLib\Exceptions\ServicesException;
use App\Domain\ApplePay\ApplePayLib\Gateway\Gateway;
use App\Domain\ApplePay\ApplePayLib\Message\Request\ValidationUrlRequest;


class ApplePayClient implements ApplePayApi
{
    protected Gateway $client;

    /**
     * @throws InvalidSettingsException
     */
    public function __construct(array $config)
    {
        $this->client = new Gateway(new Settings($config));
    }

    /**
     * @throws ServicesException
     */
    public function validationUrl(ValidationUrlRequest $data): array
    {
        return $this->client->validationUrl($data);
    }
}
