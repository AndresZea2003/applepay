<?php

namespace App\Domain\ApplePay\ApplePayLib\Cases\Traits;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Message;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

trait ResponseFromFile
{
    public function makeResponseFromFile(string $nameFileResponse): PromiseInterface
    {
        return new FulfilledPromise($this->responseFromFile($nameFileResponse));
    }

    public function responseFromFile(string $fileName): ResponseInterface
    {
        $path = __DIR__."/../../tests/Mocks/$fileName.txt";

        return Message::parseResponse(file_get_contents($path));
    }

    public function throwRequestException(RequestInterface $request, string $nameFileResponse): never
    {
        throw RequestException::create($request, $this->responseFromFile($nameFileResponse));
    }

    public function throwConnectException(RequestInterface $request): never
    {
        throw new ConnectException('connect exception', $request);
    }
}
