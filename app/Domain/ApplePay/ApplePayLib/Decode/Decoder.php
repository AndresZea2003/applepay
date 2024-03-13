<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode;

use App\Domain\ApplePay\ApplePayLib\Message\Request\DecodeRequest;
use App\Domain\ApplePay\ApplePayLib\Message\Response\DecodeResponse;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Decoder
{
    public static function make(DecodeRequest $request): DecodeResponse
    {
        // TODO decode...

        //obtener llave privada -> ecckey.key -> leer y obtener el contenido
 /*       Get AppleRootCA-G3.pem:

        Download AppleRootCA-G3.cer
        Run command: openssl x509 -inform der -in storage/app/AppleRootCA-G3.cer -out AppleRootCA-G3.pem*/

        $signature = base64_decode($request->paymentData->signature);

        if(empty($signature)) {
            throw new \RuntimeException('Signature is not a valid base64 value');
        }

        $file = tmpfile();
        fwrite($file, $signature);
        $fileMetadata = stream_get_meta_data($file);

        $certificatePath =  $fileMetadata['uri'];
        $getCertificatesCommand = ['openssl', 'pkcs7', '-inform', 'DER', '-in', $certificatePath, '-print_certs'];

        try {
            $process = new Process($getCertificatesCommand);
            $process->mustRun();

            $commandOutput = $process->getOutput();
        } catch (ProcessFailedException $e) {
            throw new \RuntimeException("Can't get certificates", 0, $e);
        }

        $certificates = str_replace("\n\n", "\n", rtrim($commandOutput));
        $certificates = str_replace("-----END CERTIFICATE-----\n", "-----END CERTIFICATE-----\n\n", $certificates);



        dd($certificates);
       // return $this->normalisePrintCerts(rtrim($commandOutput));


        return DecodeResponse::fromArray([
            'token' => $request->toArray()
        ]);

    }

    /**
     * @param array $command
     * @return string
     * @throws ProcessFailedException
     */
    private function runCommand(array $command)
    {
        $process = new Process($command);
        $process->mustRun();

        return $process->getOutput();
    }

}
