<?php

namespace App\Domain\ApplePay\ApplePayLib\Cases\Bases;

use App\Domain\ApplePay\ApplePayLib\Cases\Enums\MerchantIdEnum;
use App\Domain\ApplePay\ApplePayLib\Cases\Traits\ResponseFromFile;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;

abstract class BaseClientMock
{
    use ResponseFromFile;

    protected array $parameters;

    protected RequestInterface $request;

    protected string $url;
    public function __invoke(RequestInterface $request): PromiseInterface
    {
        $this->parameters = json_decode($request->getBody()->__toString(), true);

        try {
            $this->request = $request;
            $this->url = $request->getUri()->__toString();
            $merchantId = $this->parameters['merchantIdentifier'] ?? null;

            if ($merchantId === MerchantIdEnum::UNAUTHORIZED->value) {
                $this->throwRequestException($this->request, 'validationUrl/fails_merchant_id_not_found');
            }

            return $this->resolveEndpoint();
        } catch (\Exception $e) {
            return Create::rejectionFor($e);
        }
    }

    abstract protected function resolveEndpoint(): PromiseInterface;

}
