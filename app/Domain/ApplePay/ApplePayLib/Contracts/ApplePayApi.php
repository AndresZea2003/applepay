<?php

namespace App\Domain\ApplePay\ApplePayLib\Contracts;

use App\Domain\ApplePay\ApplePayLib\Exceptions\ServicesException;
use App\Domain\ApplePay\ApplePayLib\Message\Request\ServerRequest;

interface ApplePayApi
{
    /**
     * @throws ServicesException
     */
    public function server(ServerRequest $data): array;
}
