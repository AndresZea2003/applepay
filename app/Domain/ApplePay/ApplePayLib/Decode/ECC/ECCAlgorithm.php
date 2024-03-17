<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode\ECC;

use AESGCM\AESGCM;
use PayU\ApplePay\Decoding\Decoder\Algorithms\AlgorithmInterface;

class ECCAlgorithm
{
    public static function getSecret(string $privateKey, string $ephemeralPublicKey): string
    {
        $publicKey = openssl_pkey_get_public(self::formatKey($ephemeralPublicKey, ECCKeyEnum::PUBLIC_KEY));
        $privateKey = openssl_pkey_get_private(self::formatKey($privateKey, ECCKeyEnum::EC_PRIVATE_KEY));
        $sharedKey = openssl_pkey_derive($publicKey, $privateKey);

        return bin2hex($sharedKey);
    }

    private static function formatKey($key, ECCKeyEnum $type)
    {
        $formattedData = '-----BEGIN ' . $type->value . '-----' . PHP_EOL;
        $formattedData .= chunk_split($key, 64);
        $formattedData .= '-----END ' . $type->value . '-----' . PHP_EOL;

        return $formattedData;
    }

    public static function getSymmetricKey(string $kdfInfo, string $sharedSecret): string
    {
        if(! $sharedSecretBin = @hex2bin($sharedSecret)) {
            throw new \RuntimeException('Shared secret is not a valid hex value');
        }

        $hashRes = hash_init('sha256');
        hash_update ( $hashRes, base64_decode('AAAA'));
        hash_update ( $hashRes, base64_decode('AQ=='));
        hash_update ( $hashRes, $sharedSecretBin);
        hash_update ( $hashRes, $kdfInfo);

        return hash_final( $hashRes, true);
    }

    public static function decrypt(string $symmetricKey, string $dataToDecode, string $iv): string
    {
        $ivBinary = @hex2bin($iv);

        if($ivBinary === false) {
            throw new \RuntimeException('IV is not a valid hex value');
        }

        try {
            $data = AESGCM::decryptWithAppendedTag($symmetricKey, $ivBinary, $dataToDecode);
        } catch(\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }

        return $data;
    }
}
