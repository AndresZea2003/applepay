<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DecodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'gateway' => 'required|string',
            'instrument' => 'required|array',
            'instrument.externalWallet' => 'required|array',
            'instrument.externalWallet.type' => 'required|string', //TODO validate with Enums externalWalletTypeEnums
            'instrument.externalWallet.additional' => 'required|array',

            'instrument.externalWallet.additional.paymentData' => 'required|array',
            'instrument.externalWallet.additional.paymentData.data' => 'required|string',
            'instrument.externalWallet.additional.paymentData.signature' => 'required|string',
            'instrument.externalWallet.additional.paymentData.version' => 'required|string',
            'instrument.externalWallet.additional.paymentData.header' => 'required|array',
            'instrument.externalWallet.additional.paymentData.header.publicKeyHash' => 'required|string',
            'instrument.externalWallet.additional.paymentData.header.ephemeralPublicKey' => 'required|string',
            'instrument.externalWallet.additional.paymentData.header.transactionId' => 'required|string',

            'instrument.externalWallet.additional.paymentMethod' => 'required|array',
            'instrument.externalWallet.additional.paymentMethod.displayName' => 'required|string',
            'instrument.externalWallet.additional.paymentMethod.network' => 'required|string',
            'instrument.externalWallet.additional.paymentMethod.type' => 'required|string',

            'instrument.externalWallet.additional.transactionIdentifier' => 'required|string',
        ];
    }

    public function token(): array
    {
        return $this->input('instrument.externalWallet.additional');
    }
}
