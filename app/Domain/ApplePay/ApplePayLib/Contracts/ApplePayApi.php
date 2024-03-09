<?php

namespace App\Domain\ApplePay\ApplePayLib\Contracts;

use App\Domain\ApplePay\ApplePayLib\Exceptions\ServicesException;
use App\Domain\ApplePay\ApplePayLib\Message\Request\ValidationUrlRequest;

interface ApplePayApi
{
    /**
     * @throws ServicesException
     */
    public function validationUrl(ValidationUrlRequest $data): array;
}
