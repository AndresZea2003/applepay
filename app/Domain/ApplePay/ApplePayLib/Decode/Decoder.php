<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode;

use App\Domain\ApplePay\ApplePayLib\Decode\ECC\ECCDecoder;
use App\Domain\ApplePay\ApplePayLib\Exceptions\DecodingFailedException;
use App\Domain\ApplePay\ApplePayLib\Message\Request\DecodeRequest;
use App\Domain\ApplePay\ApplePayLib\Message\Response\DecodeResponse;

readonly class Decoder
{
    public function __construct(private DecodeRequest $request)
    {
        //
    }

    public static function make(DecodeRequest $request): self
    {
        return new self($request);
    }

    /**
     * @return DecodeResponse
     * @throws DecodingFailedException
     */
    public function decode(): DecodeResponse
    {
        /**
         * Step 1
         * --> ✅ 1a. Ensure that the certificates contain the correct custom OIDs
         *
         * --> ✅ 1b.  Ensure that the root CA is the Apple Root CA - G3. This certificate is available from
         * --> ✅ 1c. Ensure that there’s a valid X.509 chain of trust from the signature to the root CA.
         *     Specifically, ensure that the signature was created using the private key that corresponds
         *     to the leaf certificate, that the leaf certificate is signed by the intermediate CA,
         *     and that the intermediate CA is signed by the Apple Root CA - G3.
         *
         * --> ✅ 1d. Validate the token’s signature. For ECC (EC_v1), ensure that the signature is a valid Ellyptical Curve Digital
         *      Signature Algorithm (ECDSA);
         *
         * --> ✅ 1e.Inspect the Cryptographic Message Syntax (CMS) signing time of the signature,
         *      as defined by section 11.3 of RFC 5652.
         */

        $paymentData = $this->request->paymentData;
        $rootCACertificateContent = $this->request->rootCACertificateContent;
        $expirationTime = $this->request->expirationTime;

        try {
            PKCS7SignatureValidator::make($paymentData, $rootCACertificateContent, $expirationTime)->validate();
        } catch (\Exception $e) {
            throw new DecodingFailedException($e->getMessage(), $e->getCode(), $e);
        }

        /**
         * ✅ Step 2: Use the value of the publicKeyHash key to determine which merchant public key Apple used, and then retrieve the corresponding merchant public key certificate and private key.
         *
         * ✅ Step 3: Restore the symmetric key. For instructions, see Restoring the symmetric key.
         *
         * ✅ Step 4: Use the symmetric key to decrypt the value of the data key:
         *
         * ✅ For ECC (EC_v1), decrypt the data key using AES–256 (id-aes256-GCM 2.16.840.1.101.3.4.1.46), with an initialization vector of 16 null bytes and no associated authentication data.
         *
         * 👀 For RSA (RSA_v1), decrypt the data key using AES–128 (id-aes128-GCM 2.16.840.1.101.3.4.1.6), with an initialization vector of 16 null bytes and no associated authentication data.
         *
         * 👀 After you complete Step 4, the payment data in the data value of the payment token structure is decrypted. Use the decrypted payment data and information you have about the transaction to validate that transaction.
         *
         * 👀 Step 5: Confirm that you haven't already credited this payment by verifying that no payment with the same transactionId shows as processed. For efficiency, consider only those payments with a transaction time that’s within the 5-minute time window of the current transactionId, as explained in the last bullet point of Step 1.
         *
         * 👀  Step 6: Verify the transaction details using information from the merchant about the Apple Pay payment request and other transaction information:
         *
         * 👀 Check that the currencyCode matches the currency code in the original Apple Pay payment request.
         *
         * 👀 Check that the transactionAmount is correct, as compared with the total charge of the transaction.
         *
         * 👀 Check that the applicationData field matches the hash of the data the original payment request used, and that the data is correct. For example, check that an order number in the data from the original payment request is the order number to which you, the payment processor, are applying this payment. For more information, see applicationData in PKPaymentRequest. For transactions that initiate in Apple Pay on the Web, see applicationData in ApplePayPaymentRequest and applicationData in ApplePayRequest.
         *
         * ✅ Step 7: If the signature is valid, the hash values match, and your transaction validation passes, use the decrypted payment data to process the payment. Otherwise, ignore the transaction.
         */

        $data =  ECCDecoder::make($this->request->privateKey, $this->request->merchantId, $paymentData)->decode();
        return DecodeResponse::fromArray(json_decode($data, true));
    }
}
