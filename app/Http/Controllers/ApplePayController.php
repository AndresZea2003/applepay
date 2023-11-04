<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ApplePayController extends Controller
{
    //
    public function appleServer(Request $request) {

        $client = new Client();

        $response = 'dad';
        try {
            $response = $client->request('POST', $request->validationUrl, [
                'cert' => [base_path('certificados/apple_pay.pem'), 'password']
            ]);
            dd($response);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            // Manejo del error
            echo $e->getMessage();
        }


        return $response;



//        $client = new Client();
//
//        $response = $client->request('POST', $request->validationUrl);
//
//        return $response;


//        $statusCode = $response->getStatusCode();
//        $body = $response->getBody()->getContents();

        $data = array(
            'domains' => array('https://www.example.com', 'https://www.mystore.com', 'https://applepay.test'),
            'merchantIdentifier' => 'com.placetopay.test',
            'websiteDomain' => 'applepay.test',
//            'partnerInternalMerchantIdentifier' => 'example-123',
            'displayName' => 'Test Placetopay'
        );

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            )
        );


//        $context  = stream_context_create($options);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
                'cafile' => base_path('certificados/apple_pay.pem')
            ]
        ]);

        $response = file_get_contents($request->validationUrl, false, $context);

        dd($response);


        return 'da';
    }
}
