<?php

namespace App\Http\Controllers;

use App\Domain\ApplePay\ApplePayLib\Exceptions\DecodingFailedException;
use App\Domain\ApplePay\ApplePayLib\Exceptions\ServicesException;
use App\Domain\ApplePay\Services\ApplePayServices;
use App\Http\Requests\DecodeRequest;
use App\Http\Requests\ValidationRequest;
use Illuminate\Http\JsonResponse;

class ApplePayV1Controller extends Controller
{
    public function validationUrl(ValidationRequest $request, ApplePayServices $applePayServices): JsonResponse|array
    {
        try {
            $response =  $applePayServices->validationUrl($request->validationUrl());
        }catch (ServicesException $e){
           return response()->json(['message' => $e->getMessage(), $e->getCode()]);
        }

        return $response->toArray();

    }

    /**
     * @throws DecodingFailedException
     */
    public function decode(DecodeRequest $request, ApplePayServices $applePayServices): array
    {
        //PROCESS -> isExternalWallet type applepay -> information -> 3DS|process


        return $applePayServices->decode($request->paymentData())->toArray();
    }
}
