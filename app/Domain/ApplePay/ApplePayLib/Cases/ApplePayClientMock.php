<?php

namespace App\Domain\ApplePay\ApplePayLib\Cases;

use App\Domain\ApplePay\ApplePayLib\Cases\Bases\BaseClientMock;
use App\Domain\ApplePay\ApplePayLib\Cases\Enums\MerchantIdEnum;
use GuzzleHttp\Promise\PromiseInterface;

class ApplePayClientMock extends BaseClientMock
{
    protected function resolveEndpoint(): PromiseInterface
    {
        return match (true) {
            str_contains($this->url, '/validation') => $this->validationUrl(),
            default => $this->throwRequestException($this->request, '404_html_response'),
        };
    }

    private function validationUrl(): PromiseInterface
    {
        $id = $this->parameters['merchantIdentifier'];

        if ($id === MerchantIdEnum::ID_NOT_VALID->value) {
            $this->throwRequestException($this->request, 'validationUrl/fails_when_merchant_identifier_not_found');
        }

        return $this->makeResponseFromFile('validationUrl/success_validation_url');

    }
}
