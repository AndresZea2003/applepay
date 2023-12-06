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
        curl_setopt($ch, CURLOPT_SSLCERT, storage_path('app/certificados/MERCHANT/ApplePay.crt.pem'));
        curl_setopt($ch, CURLOPT_SSLKEY, storage_path('app/certificados/MERCHANT/rsakey.key'));
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


$privateKey = 'MHcCAQEEIGTWsasssKoSLbIQuYuBZ+p9gwgKOzFFDhfkA50lQTiroAoGCCqGSM49AwEHoUQDQgAErUMdWXWVtKeRpN/ZobVcA/H9dbz5KIPLc1b3MKrmGvcg9BHoHPtEHbQrDY/GZ6y5S8Qm39+W3dRHrMoEu2GY6Q==';
// merchant identifier from Apple Pay Merchant Account
$appleId = 'merchant.placetopay-test';

// payment token data received from Apple Pay
$paymentData = '{
            "data": "8uIgnoSuUQ+P7LfXyi98kwB0UXbqvyEN9O7WaVffEbuDIRwFYyOmaATaG2zWlK3mwF9AadySbw1Uaw6v6MQmMZHiLoWTD4qvGEPIiqIsVTxkhxG19F57hyzSHqxShUgcxwHfj/rvkmeiFR0E1o8dwrCz5Y4cHzkUIXyCqpBt6cCTozjc5v7arTgkicNQwnu1GZVcOVHQP0ZD1hY3vB8RTbKY2EOC9oJRSpDayxj35X2BFp1hsYfDePiIS0KL0P2KfxTTl7aS/m1qEW12wewTsXPw8ztS9TRza+P3TY61bBs6zRWuPLEq50dinECUET+YgjQftalK/qbtzbj4uKYkjv3xuOyfeP40xzbhl8iauWi+Nsxaaeo+SkpUK21LNQbG5xGPfaCgU5KvwJ2x",
            "signature": "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",
            "header": {
                "publicKeyHash": "bSJELIP2nhtyslRFaUO2TY3d7q5oN8jsrI+cLQ1EBuI=",
                "ephemeralPublicKey": "MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEWA6+a08szwPeRy6zL5XNXvs/EDPEuFtm8vFqAsC8wAo/0yVJM+EUCYix4eYbjrL92YG76j6BT1EMHbk9kcMzSA==",
                "transactionId": "c388f5ae121e9d9e107dcda00d27836dd2ca0a75b235ac6e9efb1d75a2d98795"
            },
            "version": "EC_v1"
        }';


$payment2 = json_encode($request->all());
// how many seconds should the token be valid since the creation time.
$expirationTime = 315360000; // It should be changed in production to a reasonable value (a couple of minutes)

$rootCertificatePath = storage_path('app/certificados/AppleRootCA-G3.pem');

$applePayDecodingServiceFactory = new ApplePayDecodingServiceFactory();
$applePayDecodingService = $applePayDecodingServiceFactory->make();
$applePayValidator = new ApplePayValidator();

$paymentData = json_decode($payment2, true);

try {
    $applePayValidator->validatePaymentDataStructure($paymentData);
    $decodedToken = $applePayDecodingService->decode($privateKey, $appleId, $paymentData, $rootCertificatePath, $expirationTime);
    echo 'Decoded token is: '.PHP_EOL.PHP_EOL;
    var_dump($decodedToken);
} catch(DecodingFailedException $exception) {
    echo 'Decoding failed: '.PHP_EOL.PHP_EOL . $exception->getMessage();
    echo $exception->getMessage();
} catch(InvalidFormatException $exception) {
    echo 'Invalid format: '.PHP_EOL.PHP_EOL;
    echo $exception->getMessage();
}


        return dump('hola');
    }


}
