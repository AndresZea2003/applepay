<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode;

use App\Domain\ApplePay\ApplePayLib\Message\Request\DecodeRequest;
use App\Domain\ApplePay\ApplePayLib\Message\Response\DecodeResponse;

class Decoder
{
    public static function make(DecodeRequest $request): DecodeResponse
    {
        // TODO decode...

        return DecodeResponse::fromArray([
            'token' => $request->toArray()
        ]);

    }

}
