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
        $response = $this->postJson(route('api.decrypt'), [
            'gateway' => 'diners',
            'instrument' => [
                'externalWallet' => [
                    'type' => 'applepay',
                    'additional' => [
                        'paymentData' => [
                            'data' => 'Zf9AV6Q7fjQuWwJP/HRJdE5Z1i/T9CJEuxw2AF4rGMQjBXpdGe+A52pF8gIWEa44UQcbHD+HFA6v6ilqBlHJLnD515XXSL6NIuPLEWpmPi8UsBaz+Fpnu/HDwjJv2CUMeP6HOFxC0GG2xuGVSAbsIJOzssLXAkYJNHmX86/mOxaVLMAUu+JW0nyspzGR2/VbtWphdMHJ+FysC9xbmqasWCPCiXNmLA5IHyXPs5FoiscJTT/ooRaHZLA4xw2JhmnFRLighNhX7Wcs6D4DoU/H0TcJaWNpz9vRMmobRVRgYmE8zoI69KhshICkWUO42GIdtQo81whWevNNk6kQthzqNzDX3F5uv9086+Nt02WLDCzIZ0YtUqIeR1vTbM9rTC/jMneDnhYvqPP5D1E=',
                            'signature' => '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',
                            'header' => [
                                'publicKeyHash' => 'USXSgL3feiU5sYa3OYlWoyqXHW9fy1VS2Us1Y3qulMw=',
                                'ephemeralPublicKey' => 'MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAE4DpxzUf/4B1eJofZ732YNcWBa1g1zF6PMMxirvGxnY5g0/cCA7TrGOj4Nf4QINEcjnjWekmb7rlgQ3nMDGcobg==',
                                'transactionId' => 'a0c9334679695d439f9a2e21a18b469ef9c6ed3a81b4dde914e99a92a1824348'
                            ],
                            'version' => 'EC_v1'
                        ],
                        'paymentMethod' => [
                            'displayName' => 'MasterCard 1036',
                            'network' => 'MasterCard',
                            'type' => 'credit'
                        ],
                        'transactionIdentifier' => '9564344f17439072c1b02d4c5e9e31ef09d14b24529074d15d0f62091b035949'
                    ]
                ]
            ]
        ]);

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
