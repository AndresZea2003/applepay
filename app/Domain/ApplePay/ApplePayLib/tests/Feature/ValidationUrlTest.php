<?php

namespace App\Domain\ApplePay\ApplePayLib\tests\Feature;

use App\Domain\ApplePay\ApplePayLib\ApplePayClient;
use App\Domain\ApplePay\ApplePayLib\Message\Request\ValidationUrlRequest;
use App\Domain\ApplePay\ApplePayLib\tests\BasesTestCase;

class ValidationUrlTest extends BasesTestCase
{
    protected ApplePayClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->ApplePayClient();
    }

    public function testValidationUrlOk()
    {
        $data = ValidationUrlRequest::fromArray([
            'merchantId' => 'merchant_test',
            'domainName' =>  'domain_name_test',
            'displayName' =>'display_name_test',
            'validationUrl' => 'https://validation-url.com/validation',
        ]);

        $response = $this->client->validationUrl($data);
        $this->assertIsArray($response);

    }
}
