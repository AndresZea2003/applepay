<?php

namespace App\Domain\ApplePay\ApplePayLib\Gateway;

use App\Domain\ApplePay\ApplePayLib\Entities\Settings;
use App\Domain\ApplePay\ApplePayLib\Exceptions\ServicesException;
use App\Domain\ApplePay\ApplePayLib\Message\Request\ValidationUrlRequest;

readonly class Gateway
{
    public function __construct(private Settings $settings)
    {
        //
    }

    /**
     * @throws ServicesException
     */
    public function validationUrl(ValidationUrlRequest $data): array
    {
        return $this->call(url: $data->validationUrl, data: $data->toApplePayRequest());
    }

    /**
     * @throws ServicesException
     */
    private function call(string $url, array $data, string $method = 'POST'): array
    {
        try {
            $response = $this->settings->httpClient->request($method, $url, ['json' => $data,  'headers' => $this->settings->headers]);

             return json_decode($response->getBody()->__toString(), true);
        } catch (\Throwable $e) {
            throw new ServicesException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
