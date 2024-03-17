<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode\Services;

use phpseclib3\File\ASN1;
use phpseclib3\Math\BigInteger;

class Asn1Services
{
    private array $asn1;

    public function __construct(readonly string $signature)
    {
        $this->asn1 = ASN1::decodeBER(base64_decode($signature));
    }

    public static function make(string $signature): self
    {
        return new self($signature);
    }

    public function getDigestMessage(): string
    {
        $object = $this->asn1[0]['content'][1]['content'][0]['content'][4]['content'][0];
        return $object['content'][3]['content'][3]['content'][1]['content'][0]['content'];
    }

    public function getLeafCertificatePublicKey(): string
    {
        // certificate tag
        $content = $this->asn1[0]['content'][1]['content'][0]['content'][3]
        ['content'][0] // leaf certificate index
        ['content'][0] // cert_info tag
        ['content'][6]; // key tag, all contents, including headers


        $publicKey = ASN1::asn1map($content, ['type' => ASN1::TYPE_ANY, 'implicit' => true])->element;

        return trim($publicKey);
    }

    public function getLeafCertificatePublicKeyAsFormatPem(): string
    {
        $leafPublicKey = $this->getLeafCertificatePublicKey();
        return "-----BEGIN PUBLIC KEY-----\n". chunk_split(base64_encode($leafPublicKey), 64, PHP_EOL) . '-----END PUBLIC KEY-----';
    }

    public function getSignedAttributes(): string
    {
        $signedAttributes = $this->asn1[0]['content'][1]['content'][0]['content'][4];
        $signedAttributes = $signedAttributes['content'][0]['content'][3]; // [content]

        $signedAttr = ASN1::asn1map($signedAttributes, ['type' => ASN1::TYPE_ANY, 'implicit' => true])->element;
        $signedAttr[0] = chr(0x31);

        return $signedAttr;
    }

    public function getSignature(): string
    {
        return $this->asn1[0]['content'][1]['content'][0]['content'][4]['content'][0]['content'][5]['content'];
    }

    public function getSigningTime(): array|BigInteger|bool|string|ASN1\Element|null
    {
        $timeAttribute = $this->asn1[0]['content'][1]['content'][0]['content'][4]['content'][0];
        $timeAttribute = $timeAttribute['content'][3]['content'][1]['content'][1]['content'][0];
        return ASN1::asn1map($timeAttribute, ['type' => ASN1::TYPE_UTC_TIME]);
    }

}
