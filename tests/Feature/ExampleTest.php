<?php

namespace Tests\Feature;

use App\Domain\ApplePay\ApplePayLib\ApplePayClient;
use App\Domain\ApplePay\ApplePayLib\Cases\ApplePayClientMock;
use App\Domain\ApplePay\ApplePayLib\Contracts\ApplePayApi;
use App\Domain\ApplePay\ApplePayLib\DTO\ApplePayAuth;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_api_response()
    {
        $this->fakeApplePayApi();

        $this->postJson(route('api.validation'), ["validationUrl" => 'https://validation.com/validation'])
            ->assertOk()
            ->assertJsonFragment(['message' => 'OK', 'token' => 'token']);
    }


    public function getClientMock($mock): Client
    {
        $handler = HandlerStack::create($mock);

        $stack = [
            'handler' => $handler,
            'http_errors' => true,
        ];

        return new Client($stack);
    }

    protected function fakeApplePayApi(): void
    {
        $data =  [
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
        ];

        $this->app->bind(ApplePayApi::class, fn () =>  new ApplePayClient($data));
    }
}
