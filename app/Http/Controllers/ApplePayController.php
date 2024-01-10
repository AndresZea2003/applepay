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

        curl_setopt($ch, CURLOPT_SSLCERT, storage_path('app/certificados/MERCHANT/merchant_id.pem'));
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
        $privateKey = 'MHcCAQEEIAbgRQC9lID5Fh7Hq0HRDiVBleN4exTkkiVNR2yPRBgmoAoGCCqGSM49AwEHoUQDQgAEI7cXhwfAsi/voXyNslttP8ujgXKRctOfZi4WPUKOudny9UcDRl2ePrx5qxL4x4ETt0MoZqs6wj3tlx4ZkYfc6g==';
// merchant identifier from Apple Pay Merchant Account

        $appleId = 'merchant.placetopay-test';
// payment token data received from Apple Pay
        $paymentData = json_encode($request->all());
// how many seconds should the token be valid since the creation time.

        $expirationTime = 315360000; // It should be changed in production to a reasonable value (a couple of minutes)
        $rootCertificatePath =  storage_path('app/certificados/AppleRootCA-G3.pem');
        $applePayDecodingServiceFactory = new ApplePayDecodingServiceFactory();
        $applePayDecodingService = $applePayDecodingServiceFactory->make();
        $applePayValidator = new ApplePayValidator();
        $paymentData = json_decode($paymentData, true);
        try {
            $applePayValidator->validatePaymentDataStructure($paymentData);
            $decodedToken = $applePayDecodingService->decode($privateKey, $appleId, $paymentData, $rootCertificatePath, $expirationTime);
            echo 'Decoded token is: '.PHP_EOL.PHP_EOL;
            dump($decodedToken);
        } catch(DecodingFailedException $exception) {
            echo 'Decoding failed: '.PHP_EOL.PHP_EOL;
            echo $exception->getMessage();
        } catch(InvalidFormatException $exception) {
            echo 'Invalid format: '.PHP_EOL.PHP_EOL;
            echo $exception->getMessage();
        }
    }


}
