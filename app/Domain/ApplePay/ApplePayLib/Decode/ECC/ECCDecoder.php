<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode\ECC;


use App\Domain\ApplePay\ApplePayLib\DTO\Decode\PaymentData;
use App\Domain\ApplePay\ApplePayLib\Exceptions\DecodingFailedException;

Readonly class ECCDecoder
{
    private const IV = '00000000000000000000000000000000';

   private const CYPHER = 'id-aes256-GCM';

    public function __construct(
        private string $privateKey,
        private string $merchantId,
        private PaymentData $paymentData
    )
    {
        //
    }

    public static function make(string $privateKey, string $merchantId, PaymentData $paymentData): self
    {
        return new self($privateKey, $merchantId,$paymentData);
    }


    /**
     * @throws DecodingFailedException
     */
    public function decode(): string
    {
        try {
            $sharedSecret =  ECCAlgorithm::getSecret($this->privateKey, $this->paymentData->header->ephemeralPublicKey);
            $kdfInfo = $this->getKdfInfo($this->merchantId);
            $symmetricKey = ECCAlgorithm::getSymmetricKey($kdfInfo, $sharedSecret);

            return ECCAlgorithm::decrypt($symmetricKey, base64_decode($this->paymentData->data), self::IV);
        }catch (\Exception $e){
            throw new DecodingFailedException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function getKdfInfo(string $merchantAppleId): string
    {
        return chr(0x0D) . self::CYPHER . 'Apple' . hash('sha256', trim($merchantAppleId), true);
    }
}
