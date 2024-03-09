<?php

namespace App\Http\Controllers;

use App\Domain\ApplePay\ApplePayLib\Exceptions\ServicesException;
use App\Domain\ApplePay\Services\ApplePayServices;
use App\Http\Requests\ValidationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplePayV1Controller extends Controller
{
    public function validationUrl(ValidationRequest $request, ApplePayServices $applePayServices): JsonResponse
    {

        try {
            $response =  $applePayServices->validationUrl([
                'merchantId' => config('services.apple_pay.merchantId'),
                'domainName' => config('services.apple_pay.domainName'),
                'displayName' => config('services.apple_pay.displayName'),
                'validationUrl' => $request->validationUrl()
            ]);

        }catch (ServicesException $e){
           return response()->json(['message' => $e->getMessage(), $e->getCode()]);
        }

        return response()->json([
            'message' => 'OK',
            'token' => $response['token']
        ]);

    }

    public function decode(Request $request): string
    {
        return response()->json([
            'message' => 'decrypt'
        ]);
    }
}
