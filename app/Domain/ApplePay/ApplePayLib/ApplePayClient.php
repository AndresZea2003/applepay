<?php

namespace App\Domain\ApplePay\ApplePayLib;


use App\Domain\ApplePay\ApplePayLib\Contracts\ApplePayApi;
use App\Domain\ApplePay\ApplePayLib\Entities\Settings;
use App\Domain\ApplePay\ApplePayLib\Exceptions\InvalidSettingsException;
use App\Domain\ApplePay\ApplePayLib\Exceptions\ServicesException;
use App\Domain\ApplePay\ApplePayLib\Gateway\Gateway;
use App\Domain\ApplePay\ApplePayLib\Message\Request\ServerRequest;


class ApplePayClient implements ApplePayApi
{
    protected Gateway $client;

    /**
     * @throws InvalidSettingsException
     */
    public function __construct(protected readonly array $config)
    {
        $this->client = new Gateway(new Settings($config));
    }


    /*   protected RestClient $client;


       public function __construct(protected readonly array $config)
       {
           $this->client = new RestClient(new Settings($config));
       }*/

    /**
     * @throws ServicesException
     */
    public function server(ServerRequest $data): array
    {
        return $this->client->server($data);
    }
}
