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

        //$content =  file_get_contents(storage_path('app/certificados/AppleRootCA-G3.cer'));
        $content =  file_get_contents(base_path('/AppleRootCA-G3.cer'));
        $expirationTime = 315360000;

        return $applePayServices->decode($request->token(), $content, $expirationTime)->toArray();
    }
}
