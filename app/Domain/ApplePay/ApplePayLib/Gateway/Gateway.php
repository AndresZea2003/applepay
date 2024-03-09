<?php

namespace App\Domain\ApplePay\ApplePayLib\Gateway;

use App\Domain\ApplePay\ApplePayLib\Entities\Settings;
use App\Domain\ApplePay\ApplePayLib\Exceptions\ServicesException;
use App\Domain\ApplePay\ApplePayLib\Message\Request\ServerRequest;

readonly class Gateway
{
    public function __construct(private Settings $settings)
    {
        //
    }

    /**
     * @throws ServicesException
     */
    public function server(ServerRequest $data): array
    {
        return $this->call(endpoint: $data->validationUrl, data: $data->toArray());
    }

    /**
     * @throws ServicesException
     */
    private function call(string $endpoint, array $data, string $method = 'POST'): array
    {
        try {
            $response = $this->settings->httpClient->request($method, $endpoint, ['json' => $data]);

             return json_decode($response->getBody()->__toString(), true);
        } catch (\Throwable $e) {
            throw new ServicesException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
