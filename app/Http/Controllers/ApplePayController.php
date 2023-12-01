<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApplePayController extends Controller
{
    //
    public function appleServer(Request $request)
    {

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withOptions([
                'cert' => '/Users/andreszea/.config/valet/Certificates/applepay.test.crt', // Ruta al certificado CSR
                'ssl_key' => '/Users/andreszea/.config/valet/Certificates/applepay.test.key', // Ruta a la llave privada
            ])->post($request->validationUrl . '/paymentSession', [

            ]);

            return $response;
        } catch (\Exception $e){
            return "No logro conectar" . $e->getMessage();
        }


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


}
