<?php

namespace App\Domain\ApplePay\ApplePayLib\tests;

use App\Domain\ApplePay\ApplePayLib\ApplePayClient;
use App\Domain\ApplePay\ApplePayLib\Cases\ApplePayClientMock;
use App\Domain\ApplePay\ApplePayLib\DTO\ApplePayAuth;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Tests\TestCase;

class BasesTestCase extends TestCase
{
    public function getClientMock($mock): Client
    {
        $handler = HandlerStack::create($mock);

        $stack = [
            'handler' => $handler,
            'http_errors' => true,
        ];

        return new Client($stack);
    }

    protected function ApplePayClient(): ApplePayClient
    {
        return new ApplePayClient([
            'auth' => ApplePayAuth::fromArray([
                'cert' => 'cert_test',
                'sslKey' => 'sslKey_test',
                'sslKeyPassword' => 'sslKeyPassword_test',
            ]),
            'httpClient' => $this->getClientMock(new ApplePayClientMock()),
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api' => 'custom-header',
            ],
        ]);
    }
}
