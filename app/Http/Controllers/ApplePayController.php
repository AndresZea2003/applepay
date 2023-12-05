<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PayU\ApplePay\ApplePayDecodingServiceFactory;
use PayU\ApplePay\ApplePayValidator;
use PayU\ApplePay\Exception\DecodingFailedException;
use PayU\ApplePay\Exception\InvalidFormatException;

class ApplePayController extends Controller
{
    //
    public function appleServer(Request $request)
    {

        $ch = curl_init();

        $data = '{"merchantIdentifier":"'.'merchant.placetopay-test'.'", "domainName":"'.'applepay-e9tjn.ondigitalocean.app'.'", "displayName":"'.'Test Placetopay'.'"}';

        curl_setopt($ch, CURLOPT_URL, $request->validationUrl);
        curl_setopt($ch, CURLOPT_SSLCERT, storage_path('app/certificados/ApplePay.crt.pem'));
        curl_setopt($ch, CURLOPT_SSLKEY, storage_path('app/certificados/ApplePay.key.pem'));
        curl_setopt($ch, CURLOPT_SSLKEYPASSWD, 'Admin123');
        //curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        //curl_setopt($ch, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1_2');
        //curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'rsa_aes_128_gcm_sha_256,ecdhe_rsa_aes_128_gcm_sha_256');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        if(curl_exec($ch) === false)
        {
            echo '{"curlError":"' . curl_error($ch) . '"}';
        }

        // close cURL resource, and free up system resources
        curl_close($ch);





        /*
        dump('Inicia llamado a Servicio de Apple', 'La url es          -------->          ' . $request->validationUrl);


        $appleUrl = $request->validationUrl;

        try {
            $client = new Client(['verify' => false]);

            $cert_path = storage_path('app/certificados/merchant_id.pem');
            $key_path = storage_path('app/certificados/key.pem');

            $response = $client->request('POST', $appleUrl, [
                'cert' => $cert_path,
                'ssl_key' => $key_path,
                'json' => [
                    'merchantIdentifier' => 'merchant.placetopay-test',
                    'domainName' => 'applepay-e9tjn.ondigitalocean.app',
                    'displayName' => 'Test Placetopay'
                ]
            ]);

            return response()->json(json_decode($response->getBody(), true));
        } catch (RequestException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }




        try {
            $response = Http::asJson()->post($request->validationUrl, []);

            dd('dad', $response->json());
        } catch (\Exception $e) {

        }


        */





        /*
        try {
            let = httpsAgent = new https.Agent({
                rejectUnauthorized: false,
                cert: await fs.readFileSync(
                path.join(_dirname, "/certificates/certificate_sandbox.pem"
                ),
                key: fs.readFileSync(
                    path.join(_dirname, "/certificates/certificate_sandbox.key")
                )
            });


                let response = await axios.post(
                appleUrl,
        {
            merchantIdentifier: "merchant.placetopay-test",
            domainName: "applepay-e9tjn.ondigitalocean.app",
            displayName: "Test Placetopay"
    },
        {
            httpsAgent
        }
    );
                res.send(response.data);
        } catch (er) {
            res.send(er);
        }

     */








//        try {
//            $response = Http::withHeaders([
//                'Content-Type' => 'application/json',
//            ])->withOptions([
//                'cert' => '/Users/andreszea/.config/valet/Certificates/applepay.test.crt', // Ruta al certificado CSR
//                'ssl_key' => '/Users/andreszea/.config/valet/Certificates/applepay.test.key', // Ruta a la llave privada
//            ])->post($request->validationUrl . '/paymentSession', [
//
//            ]);
//
//            return $response;
//        } catch (\Exception $e){
//            return "No logro conectar" . $e->getMessage();
//        }




////        $validationURL = $request->input('validationURL');
//
//        $validationURL = $request->validationUrl)
//
//        // Realizar la validación del comerciante...
//        $merchantSession = $this->performValidation($validationURL);
//
//        return response()->json($merchantSession);
//
//
////        https://apple-pay-gateway.apple.com/paymentservices/paymentSession
////        return $request;
//
//        dd($request->validationUrl);
//
//
//
//        $response = Http::withHeaders([
//            'Content-Type' => 'application/json',
//        ])->post('https://apple-pay-gateway.apple.com/paymentservices/startSession');
//
//
//
//
//        if ($response) {
//            return 'Peticion realizada con exito: ' . $response;
//        } else {
//            return 'Error al realizar la solicitud: ';
//        }
//
//        return 'dad';
//
//        $client = new Client();
//
//        $response = 'dad';
//        try {
//            $response = $client->request('POST', $request->validationUrl, [
//                'cert' => [base_path('certificados/apple_pay.pem'), 'password']
//            ]);
//            dd($response);
//        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
//            // Manejo del error
//            echo $e->getMessage();
//        }
//
//
//        return $response;
//
//
//
////        $client = new Client();
////
////        $response = $client->request('POST', $request->validationUrl);
////
////        return $response;
//
//
////        $statusCode = $response->getStatusCode();
////        $body = $response->getBody()->getContents();
//
//        $data = array(
//            'domains' => array('https://www.example.com', 'https://www.mystore.com', 'https://applepay.test'),
//            'merchantIdentifier' => 'com.placetopay.test',
//            'websiteDomain' => 'applepay.test',
////            'partnerInternalMerchantIdentifier' => 'example-123',
//            'displayName' => 'Test Placetopay'
//        );
//
//        $options = array(
//            'http' => array(
//                'header'  => "Content-type: application/json\r\n",
//                'method'  => 'POST',
//                'content' => json_encode($data),
//            )
//        );
//
//
////        $context  = stream_context_create($options);
//        $context = stream_context_create([
//            'ssl' => [
//                'verify_peer' => true,
//                'verify_peer_name' => true,
//                'cafile' => base_path('certificados/apple_pay.pem')
//            ]
//        ]);
//
//        $response = file_get_contents($request->validationUrl, false, $context);
//
//        dd($response);
//
//
//        return 'da';
    }

    private function performValidation($validationURL)
    {
        // Crear una nueva instancia de cURL
        $ch = curl_init();

        // Configurar las opciones de cURL
        curl_setopt($ch, CURLOPT_URL, $validationURL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'merchantIdentifier' => 'merchant.placetopay-test',
            'domainName' => 'example.com',
            'displayName' => 'Test Placetopay'
        ]));

        // Realizar la solicitud de validación a Apple
        $response = curl_exec($ch);

        // Cerrar la instancia de cURL
        curl_close($ch);

        // Decodificar la respuesta de Apple
        $merchantSession = json_decode($response, true);

        return $merchantSession;
    }

    public function decode(Request $request)
    {


$privateKey = 'MHcCAQEEIEV17KjFHD0W014fRRnbM4Un9gkOEYhJz/A/qWPd9PIloAoGCCqGSM49AwEHoUQDQgAESRBiGT+GnbM3r1M4fhYEFUKe6EHA+z6r2ctjtWqeAs9wI48MBoGKFrwWqY/zbzMNYgaOm+DnUMjF8v8v1nMtag==';

// merchant identifier from Apple Pay Merchant Account
$appleId = 'merchant.sandbox.payu';

// payment token data received from Apple Pay
$paymentData = '{"version":"EC_v1","data":"UeSmPQQawN6olhB0LNY1cZZ000mToaFGdkMN6OxU9lAAPa9IWPDN9tISOknANVSdkVXi51y2kCqaimjFFuOWFxLNngiZtHdPHLNuz8tgLLVKnvd6mxc40Iz9sQmg93K4BNHGSxC69BGz5QrcrXP3BE96aWtuty3Kuzz+PCiHvEfhMwnW\/EpERJdQrJrDmzUwydRhKNS9Cu1ohLHeQo0ngKjbFon0Io5133h1jYhDsYBnL4vOeNDMFKKH+Rv6nG+U4dsBG1DNXFitMywWVBfGcPWtgEMUQcIJnVP61TTMhQl6dHe13QaWOdW+YYKBMZuBvyUKH+7WuFWZWAEn8m4+Ase0rglxpX14tiprGagjqxRm7QH4zQK4lrVy5JD2DQ26fAUtlvlGxBJz2TAht0FU","signature":"MIAGCSqGSIb3DQEHAqCAMIACAQExDzANBglghkgBZQMEAgEFADCABgkqhkiG9w0BBwEAAKCAMIID5jCCA4ugAwIBAgIIaGD2mdnMpw8wCgYIKoZIzj0EAwIwejEuMCwGA1UEAwwlQXBwbGUgQXBwbGljYXRpb24gSW50ZWdyYXRpb24gQ0EgLSBHMzEmMCQGA1UECwwdQXBwbGUgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkxEzARBgNVBAoMCkFwcGxlIEluYy4xCzAJBgNVBAYTAlVTMB4XDTE2MDYwMzE4MTY0MFoXDTIxMDYwMjE4MTY0MFowYjEoMCYGA1UEAwwfZWNjLXNtcC1icm9rZXItc2lnbl9VQzQtU0FOREJPWDEUMBIGA1UECwwLaU9TIFN5c3RlbXMxEzARBgNVBAoMCkFwcGxlIEluYy4xCzAJBgNVBAYTAlVTMFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEgjD9q8Oc914gLFDZm0US5jfiqQHdbLPgsc1LUmeY+M9OvegaJajCHkwz3c6OKpbC9q+hkwNFxOh6RCbOlRsSlaOCAhEwggINMEUGCCsGAQUFBwEBBDkwNzA1BggrBgEFBQcwAYYpaHR0cDovL29jc3AuYXBwbGUuY29tL29jc3AwNC1hcHBsZWFpY2EzMDIwHQYDVR0OBBYEFAIkMAua7u1GMZekplopnkJxghxFMAwGA1UdEwEB\/wQCMAAwHwYDVR0jBBgwFoAUI\/JJxE+T5O8n5sT2KGw\/orv9LkswggEdBgNVHSAEggEUMIIBEDCCAQwGCSqGSIb3Y2QFATCB\/jCBwwYIKwYBBQUHAgIwgbYMgbNSZWxpYW5jZSBvbiB0aGlzIGNlcnRpZmljYXRlIGJ5IGFueSBwYXJ0eSBhc3N1bWVzIGFjY2VwdGFuY2Ugb2YgdGhlIHRoZW4gYXBwbGljYWJsZSBzdGFuZGFyZCB0ZXJtcyBhbmQgY29uZGl0aW9ucyBvZiB1c2UsIGNlcnRpZmljYXRlIHBvbGljeSBhbmQgY2VydGlmaWNhdGlvbiBwcmFjdGljZSBzdGF0ZW1lbnRzLjA2BggrBgEFBQcCARYqaHR0cDovL3d3dy5hcHBsZS5jb20vY2VydGlmaWNhdGVhdXRob3JpdHkvMDQGA1UdHwQtMCswKaAnoCWGI2h0dHA6Ly9jcmwuYXBwbGUuY29tL2FwcGxlYWljYTMuY3JsMA4GA1UdDwEB\/wQEAwIHgDAPBgkqhkiG92NkBh0EAgUAMAoGCCqGSM49BAMCA0kAMEYCIQDaHGOui+X2T44R6GVpN7m2nEcr6T6sMjOhZ5NuSo1egwIhAL1a+\/hp88DKJ0sv3eT3FxWcs71xmbLKD\/QJ3mWagrJNMIIC7jCCAnWgAwIBAgIISW0vvzqY2pcwCgYIKoZIzj0EAwIwZzEbMBkGA1UEAwwSQXBwbGUgUm9vdCBDQSAtIEczMSYwJAYDVQQLDB1BcHBsZSBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTETMBEGA1UECgwKQXBwbGUgSW5jLjELMAkGA1UEBhMCVVMwHhcNMTQwNTA2MjM0NjMwWhcNMjkwNTA2MjM0NjMwWjB6MS4wLAYDVQQDDCVBcHBsZSBBcHBsaWNhdGlvbiBJbnRlZ3JhdGlvbiBDQSAtIEczMSYwJAYDVQQLDB1BcHBsZSBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTETMBEGA1UECgwKQXBwbGUgSW5jLjELMAkGA1UEBhMCVVMwWTATBgcqhkjOPQIBBggqhkjOPQMBBwNCAATwFxGEGddkhdUaXiWBB3bogKLv3nuuTeCN\/EuT4TNW1WZbNa4i0Jd2DSJOe7oI\/XYXzojLdrtmcL7I6CmE\/1RFo4H3MIH0MEYGCCsGAQUFBwEBBDowODA2BggrBgEFBQcwAYYqaHR0cDovL29jc3AuYXBwbGUuY29tL29jc3AwNC1hcHBsZXJvb3RjYWczMB0GA1UdDgQWBBQj8knET5Pk7yfmxPYobD+iu\/0uSzAPBgNVHRMBAf8EBTADAQH\/MB8GA1UdIwQYMBaAFLuw3qFYM4iapIqZ3r6966\/ayySrMDcGA1UdHwQwMC4wLKAqoCiGJmh0dHA6Ly9jcmwuYXBwbGUuY29tL2FwcGxlcm9vdGNhZzMuY3JsMA4GA1UdDwEB\/wQEAwIBBjAQBgoqhkiG92NkBgIOBAIFADAKBggqhkjOPQQDAgNnADBkAjA6z3KDURaZsYb7NcNWymK\/9Bft2Q91TaKOvvGcgV5Ct4n4mPebWZ+Y1UENj53pwv4CMDIt1UQhsKMFd2xd8zg7kGf9F3wsIW2WT8ZyaYISb1T4en0bmcubCYkhYQaZDwmSHQAAMYIBjTCCAYkCAQEwgYYwejEuMCwGA1UEAwwlQXBwbGUgQXBwbGljYXRpb24gSW50ZWdyYXRpb24gQ0EgLSBHMzEmMCQGA1UECwwdQXBwbGUgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkxEzARBgNVBAoMCkFwcGxlIEluYy4xCzAJBgNVBAYTAlVTAghoYPaZ2cynDzANBglghkgBZQMEAgEFAKCBlTAYBgkqhkiG9w0BCQMxCwYJKoZIhvcNAQcBMBwGCSqGSIb3DQEJBTEPFw0xODAxMjYxMzU1MThaMCoGCSqGSIb3DQEJNDEdMBswDQYJYIZIAWUDBAIBBQChCgYIKoZIzj0EAwIwLwYJKoZIhvcNAQkEMSIEIE3cv23pCPQcC4NYY9JgJPyF\/Xmrxnm+lwHQqfvM6Sb1MAoGCCqGSM49BAMCBEgwRgIhAO2PZavNEzOYVVlfnnd+FK+YFMAY+KFAX0x2zYMS9M3IAiEA5rEdGSq\/ljS\/xvLye9zJtSmtzoDuNAjdaDtbjZ21ozAAAAAAAAA=","header":{"ephemeralPublicKey":"MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAE8VfEh4f\/PF4eTCblWerBJCgpg1BrhZZbpIroEfw\/7OZkkVyAlneu5SIBZXbrQRTrHOfh16Lue4t0y99brvzGFA==","publicKeyHash":"zZPAYNrLOwPbRsav95FZTIYlKF6dULquEHppV6TRPmc=","transactionId":"07e1cfeea3952ca4fcc87d080cf47feb3c3ab2e8fe7261db26457fb361e9d02e"}}';

// how many seconds should the token be valid since the creation time.
$expirationTime = 315360000; // It should be changed in production to a reasonable value (a couple of minutes)

$rootCertificatePath = __DIR__ . '/AppleRootCA-G3.pem';

$applePayDecodingServiceFactory = new ApplePayDecodingServiceFactory();
$applePayDecodingService = $applePayDecodingServiceFactory->make();
$applePayValidator = new ApplePayValidator();

$paymentData = json_decode($paymentData, true);

try {
    $applePayValidator->validatePaymentDataStructure($paymentData);
    $decodedToken = $applePayDecodingService->decode($privateKey, $appleId, $paymentData, $rootCertificatePath, $expirationTime);
    echo 'Decoded token is: '.PHP_EOL.PHP_EOL;
    var_dump($decodedToken);
} catch(DecodingFailedException $exception) {
    echo 'Decoding failed: '.PHP_EOL.PHP_EOL;
    echo $exception->getMessage();
} catch(InvalidFormatException $exception) {
    echo 'Invalid format: '.PHP_EOL.PHP_EOL;
    echo $exception->getMessage();
}


        return dump('hola');
    }


}
