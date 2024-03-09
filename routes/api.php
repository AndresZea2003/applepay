<?php

use App\Http\Controllers\ApplePayController;
use App\Http\Controllers\ApplePayV1Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PayU\ApplePay\ApplePayDecodingServiceFactory;
use PayU\ApplePay\ApplePayValidator;
use PayU\ApplePay\Exception\DecodingFailedException;
use PayU\ApplePay\Exception\InvalidFormatException;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/applepay', [ApplePayController::class, 'appleServer'])->name('apple');
Route::post('/decrypt', [ApplePayController::class, 'decode'])->name('decrypt');

Route::prefix('/v1/applepay')->group(function () {
    Route::post('/server', [ApplePayV1Controller::class, 'server'])->name('api.server');
    Route::post('/decode', [ApplePayV1Controller::class, 'decode'])->name('api.decrypt');
});




//
//Route::post('/decrypt', function (Request $request) {
//
//
//    $privateKey = "MIIGIDCCBQigAwIBAgIQL8vc6cPvzFuJrNRGnYl9TzANBgkqhkiG9w0BAQsFADB1
//MUQwQgYDVQQDDDtBcHBsZSBXb3JsZHdpZGUgRGV2ZWxvcGVyIFJlbGF0aW9ucyBD
//ZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTELMAkGA1UECwwCRzMxEzARBgNVBAoMCkFw
//cGxlIEluYy4xCzAJBgNVBAYTAlVTMB4XDTIzMTIwMTIyMDczNloXDTI1MTIzMDIy
//MDczNVowgaIxKDAmBgoJkiaJk/IsZAEBDBhtZXJjaGFudC5wbGFjZXRvcGF5LXRl
//c3QxPTA7BgNVBAMMNEFwcGxlIFBheSBNZXJjaGFudCBJZGVudGl0eTptZXJjaGFu
//dC5wbGFjZXRvcGF5LXRlc3QxEzARBgNVBAsMCjZXSjZCSEI4R1YxIjAgBgNVBAoM
//GUVWRVJURUMgUExBQ0VUT1BBWSBTLkEuUy4wggEiMA0GCSqGSIb3DQEBAQUAA4IB
//DwAwggEKAoIBAQC5L/EA1ztuU1jZgWli+fpd4GlIbfXds8KNB5BoRqJsQ9dtaswK
//gWwLP7qFPjrHJhOVCxm9pos2SdjusEhnLRy9OLLvp08Bo1ZkKhm7y25SgdoVtFRj
//olrBfSxCAIHeaR300yzVvMjxNTFdSxu4LvPQzXlJapSDmPHs07j2fRghXxT+1IdH
//cPgalksVSo1LSVWfXt+GKTSYqG0xKhgiTDyiMkKrMxL7GQnTMX7dQ1e9pV1R6QRa
//LL6qiw+ko1WdWjAhsuAkW08+FZ9fBZxVqhQ9OtuY48vXzYjy2w0vxpOqoG78rFSW
//AG28BUCrw7QKFyNJ/xonpblSLyvk/W/5DUa7AgMBAAGjggJ8MIICeDAMBgNVHRMB
//Af8EAjAAMB8GA1UdIwQYMBaAFAn+wBWQ+a9kCpISuSYoYwyX7KeyMHAGCCsGAQUF
//BwEBBGQwYjAtBggrBgEFBQcwAoYhaHR0cDovL2NlcnRzLmFwcGxlLmNvbS93d2Ry
//ZzMuZGVyMDEGCCsGAQUFBzABhiVodHRwOi8vb2NzcC5hcHBsZS5jb20vb2NzcDAz
//LXd3ZHJnMzA5MIIBLQYDVR0gBIIBJDCCASAwggEcBgkqhkiG92NkBQEwggENMIHR
//BggrBgEFBQcCAjCBxAyBwVJlbGlhbmNlIG9uIHRoaXMgQ2VydGlmaWNhdGUgYnkg
//YW55IHBhcnR5IG90aGVyIHRoYW4gQXBwbGUgaXMgcHJvaGliaXRlZC4gUmVmZXIg
//dG8gdGhlIGFwcGxpY2FibGUgc3RhbmRhcmQgdGVybXMgYW5kIGNvbmRpdGlvbnMg
//b2YgdXNlLCBjZXJ0aWZpY2F0ZSBwb2xpY3kgYW5kIGNlcnRpZmljYXRpb24gcHJh
//Y3RpY2Ugc3RhdGVtZW50cy4wNwYIKwYBBQUHAgEWK2h0dHBzOi8vd3d3LmFwcGxl
//LmNvbS9jZXJ0aWZpY2F0ZWF1dGhvcml0eS8wEwYDVR0lBAwwCgYIKwYBBQUHAwIw
//HQYDVR0OBBYEFB+dOMRX46N7duntiN10u5gCbg5bMA4GA1UdDwEB/wQEAwIHgDBP
//BgkqhkiG92NkBiAEQgxANThBNTFDMUExMjc3RjdCRERGN0U4Qjc5RTI4NTUwODk1
//MEM2RjZGOUY0NTJDQjA0OUQwRDBDOUVCRDIwMTEwQjAPBgkqhkiG92NkBi4EAgUA
//MA0GCSqGSIb3DQEBCwUAA4IBAQA+bQ3DCFeHriZNinFcXv/HK1RH7tVeQivPcnMm
//slxphwpPIwk6bPWL7jOBXy2Rxnw5mi18Uby0WlXZ/7qRn8TFoh0LW+bm0OczxQ44
//sogt2AAzqz5CYEwgmVDbr1KwDGF9xaydQdt5xpGZ/FPPXxU0xZvMMPfEXvAFA5zy
///Q4/WWz3izpcCKZBWqpg7T7xFNhaCfNeIO/CINYjgq7NpOY4FCS9zKsba5eY1+yi
//PjQ+BEOfQNtK/PtdUye6LK+iVDfbsSHV+wTKlXaFPX2AX3qmQGGCkJFXhlBgpuXQ
//I1iLZLruMLnVbfEkM4gBE7zlsvD/dN4EJCG0bbXF4P5JH1w7";
//
//    // merchant identifier from Apple Pay Merchant Account
//    $appleId = 'merchant.placetopay-test';
//
//
//    $paymentData = $request->all()['paymentData'];
//
//
//    // how many seconds should the token be valid since the creation time.
//    $expirationTime = 315360000; // It should be changed in production to a reasonable value (a couple of minutes)
//
//    $rootCertificatePath = storage_path('app/certificados/AppleRootCA-G3.pem');
//
//    $applePayDecodingServiceFactory = new ApplePayDecodingServiceFactory();
//    $applePayDecodingService = $applePayDecodingServiceFactory->make();
//    $applePayValidator = new ApplePayValidator();
//
//    $paymentData = json_decode(json_encode($paymentData), true);
//
//
//
//    try {
//        $applePayValidator->validatePaymentDataStructure($paymentData);
//        $decodedToken = $applePayDecodingService->decode($privateKey, $appleId, $paymentData, $rootCertificatePath, $expirationTime);
//        echo 'Decoded token is: '.PHP_EOL.PHP_EOL;
//        var_dump($decodedToken);
//    } catch(DecodingFailedException $exception) {
//        echo 'Decoding failed: '.PHP_EOL.PHP_EOL;
//        echo $exception->getMessage();
//    } catch(InvalidFormatException $exception) {
//        echo 'Invalid format: '.PHP_EOL.PHP_EOL;
//        echo $exception->getMessage();
//    }
//
//
//
//})->name('decrypt');
