<script setup>

import {ref} from "vue";
import axios from "axios";

const validateMerchant = (validationUrl) => {
    console.log('La Url de validacion es:', validationUrl)

    const data = {
        merchantIdentifier: 'merchant.placetopay-test',
        displayName: 'Test Placetopay',
        validationUrl: validationUrl
    };

    let urlBase = `${window.location.origin}/api/applepay`
    console.log(urlBase)
    return axios.post(urlBase, data) // Asegúrate de devolver la promesa aquí
        .then((response) => {
            console.log(response.data);
            console.log('EXITO')
            return response.data
        })
        .catch((error) => {
            console.log('FALLO', error)
            console.error(error);
        });
}

let token = ref('Sin token')
let resMerchant = ref('No hay respuesta (Realiza un pago)')

function onApplePayButtonClicked() {

    console.log('Inicia ApplePay!')
    //PASO 1
    if (!ApplePaySession) {
        return;
    }

    //PASO 2
    // Define ApplePayPaymentRequest
    const request = {
        "countryCode": "US",
        "currencyCode": "USD",
        "merchantCapabilities": [
            "supports3DS"
        ],
        "supportedNetworks": [
            "visa",
            "masterCard",
            "amex",
            "discover"
        ],
        "total": {
            "label": "Demo (Card is not charged)",
            "type": "final",
            "amount": "1.99"
        }
    };

    const simpleRequest = {
        countryCode: "US",
        currencyCode: "USD",
        merchantCapabilities: [
            "supports3DS"
        ],
        supportedNetworks: ["visa", "mastercard", "amex", "discover"],
        total: { label: "Place To Pay", amount: "1.00"}
    }

    const recurringPaymentRequest = {
        "countryCode": "US",
        "currencyCode": "USD",
        "merchantCapabilities": [
            "supports3DS",
            "supportsDebit",
            "supportsCredit"
        ],
        "supportedNetworks": [
            "visa",
            "masterCard",
            "amex",
            "discover"
        ],
        "lineItems": [
            {
                "label": "7 Day Trial",
                "amount": "0.00",
                "paymentTiming": "recurring",
                "recurringPaymentEndDate": "2024-02-28T05:00:00.000Z"
            },
            {
                "label": "Recurring",
                "amount": "4.99",
                "paymentTiming": "recurring",
                "recurringPaymentStartDate": "2024-02-28T05:00:00.000Z"
            }
        ],
        "recurringPaymentRequest": {
            "paymentDescription": "A description of the recurring payment to display to the user in the payment sheet.",
            "regularBilling": {
                "label": "Recurring",
                "amount": "4.99",
                "paymentTiming": "recurring",
                "recurringPaymentStartDate": "2024-02-28T05:00:00.000Z"
            },
            "trialBilling": {
                "label": "7 Day Trial",
                "amount": "0.00",
                "paymentTiming": "recurring",
                "recurringPaymentEndDate": "2024-02-28T05:00:00.000Z"
            },
            "billingAgreement": "A localized billing agreement displayed to the user in the payment sheet prior to the payment authorization.",
            "managementURL": "https://applepaydemo.apple.com",
            "tokenNotificationURL": "https://applepaydemo.apple.com"
        },
        "total": {
            "label": "Recurring Demo (Card is not charged)",
            "amount": "4.99"
        }
    }

    //PASO 3
    // Create ApplePaySession
    const session = new ApplePaySession(10, recurringPaymentRequest);

    //PASO 4
    session.begin();


    //PASO 5
    session.onvalidatemerchant = async event => {
        // Call your own server to request a new merchant session.

        // const merchantSession = await validateMerchant("com.placetopay.test");

        // console.log('Inicia validacion del comercio', event.validationURL)

        console.log('Inicia Validacion del merchant!')

        /*
        let validationUrl = event.validationURL

        const merchantSession = await validateMerchant(validationUrl)

        session.completeMerchantValidation(merchantSession);

         */

        //PASO 6
        const merchantSession = await validateMerchant(event.validationURL);

        //PASO 7
        session.completeMerchantValidation(merchantSession);

        console.log('RESPUESTA AL COMPLETAR VALIDACION DEL MERCHANT', merchantSession)


        // validateMerchant(event.validationURL, function (merchantSession) {
        //     session.completeMerchantValidation(merchantSession);
        //
        // });
    };

    //PASO 8
    session.onpaymentauthorized = function(event) {
        // Aquí es donde procesarías el pago. Por ejemplo, podrías enviar los detalles del pago a tu servidor para que sean procesados.
        // Asegúrate de que esta función devuelva una promesa que se resuelva cuando el pago se haya procesado correctamente.


        //PASO 9 CONSUMIR BACK PARA DESENCRIPTAR TOKEN

        token.value = event.payment

        console.log('RESPUESTA : ', event)
        console.log('PAYMENT : ', event.payment)

        // let promise = processPayment(event.payment); // Asume que processPayment es tu función que procesa el pago


        //PASO 10

        session.completePayment(ApplePaySession.STATUS_SUCCESS);

        // promise.then(function(success) {
        //     if (success) {
        //         // Si el pago se procesó correctamente, debes llamar a completePayment con ApplePaySession.STATUS_SUCCESS
        //         session.completePayment(ApplePaySession.STATUS_SUCCESS);
        //     } else {
        //         // Si hubo un error al procesar el pago, debes llamar a completePayment con ApplePaySession.STATUS_FAILURE
        //         session.completePayment(ApplePaySession.STATUS_FAILURE);
        //     }
        // });

    };

    session.oncancel = event => {
        // Payment cancelled by WebKit
    };

}
</script>

<template>

    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md rounded-lg shadow-lg p-8 bg-white">
            <h2 class="text-2xl font-bold mb-4">Pagar con Apple Pay</h2>
            <div class="flex items-center justify-between mb-4">
                <p class="text-lg font-semibold">Total:</p>
                <p class="text-lg font-semibold">$999.00</p>
            </div>
            <div class="rounded-lg border border-gray-300 p-4 mb-4">
                <img src="https://developer.apple.com/news/images/og/apple-pay-og-twitter.jpg" alt="">
                <p class="text-lg font-semibold mb-2">Producto</p>
                <p class="text-sm text-gray-500">Test integracion de Apple Pay</p>
            </div>
            <div class="flex flex-col space-y-4">
                <apple-pay-button @click="onApplePayButtonClicked()" buttonstyle="black" type="plain" locale="es"></apple-pay-button>

                <div class="rounded-lg border border-gray-300 p-4">
                    <span class="font-bold text-2xl text-gray-500">Respuesta del Servicio:</span> <br> <br>
                    <span class="font-bold text-xl">Validacion Merchant:</span> <br>
                    {{resMerchant}} <br> <br>
                    <span class="font-bold text-xl">Token:</span> <br>
                    {{token}}
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.buttonVisible {
    display: block;
}

apple-pay-button {
  --apple-pay-button-height: 40px;
}
</style>
