<!--<script setup>-->
<!--const banana = () => {-->
<!--  alert('banana')-->
<!--}-->

<!--function onApplePayButtonClicked() {-->

<!--    if (!ApplePaySession) {-->
<!--        return;-->
<!--    }-->

<!--    // Define ApplePayPaymentRequest-->
<!--    const request = {-->
<!--        "countryCode": "US",-->
<!--        "currencyCode": "USD",-->
<!--        "merchantCapabilities": [-->
<!--            "supports3DS"-->
<!--        ],-->
<!--        "supportedNetworks": [-->
<!--            "visa",-->
<!--            "masterCard",-->
<!--            "amex",-->
<!--            "discover"-->
<!--        ],-->
<!--        "total": {-->
<!--            "label": "Demo (Card is not charged)",-->
<!--            "type": "final",-->
<!--            "amount": "1.99"-->
<!--        }-->
<!--    };-->

<!--    // Create ApplePaySession-->
<!--    const session = new ApplePaySession(3, request);-->
<!--    console.log(session)-->

<!--    session.onvalidatemerchant = async event => {-->
<!--        // Call your own server to request a new merchant session.-->
<!--        const merchantSession = await validateMerchant();-->
<!--        session.completeMerchantValidation(merchantSession);-->
<!--    };-->

<!--    session.onpaymentmethodselected = event => {-->
<!--        // Define ApplePayPaymentMethodUpdate based on the selected payment method.-->
<!--        // No updates or errors are needed, pass an empty object.-->
<!--        const update = {};-->
<!--        session.completePaymentMethodSelection(update);-->
<!--    };-->

<!--    session.onshippingmethodselected = event => {-->
<!--        // Define ApplePayShippingMethodUpdate based on the selected shipping method.-->
<!--        // No updates or errors are needed, pass an empty object.-->
<!--        const update = {};-->
<!--        session.completeShippingMethodSelection(update);-->
<!--    };-->

<!--    session.onshippingcontactselected = event => {-->
<!--        // Define ApplePayShippingContactUpdate based on the selected shipping contact.-->
<!--        const update = {};-->
<!--        session.completeShippingContactSelection(update);-->
<!--    };-->

<!--    session.onpaymentauthorized = event => {-->
<!--        // Define ApplePayPaymentAuthorizationResult-->
<!--        const result = {-->
<!--            "status": ApplePaySession.STATUS_SUCCESS-->
<!--        };-->
<!--        session.completePayment(result);-->
<!--    };-->

<!--    session.oncouponcodechanged = event => {-->
<!--        // Define ApplePayCouponCodeUpdate-->
<!--        const newTotal = calculateNewTotal(event.couponCode);-->
<!--        const newLineItems = calculateNewLineItems(event.couponCode);-->
<!--        const newShippingMethods = calculateNewShippingMethods(event.couponCode);-->
<!--        const errors = calculateErrors(event.couponCode);-->

<!--        session.completeCouponCodeChange({-->
<!--            newTotal: newTotal,-->
<!--            newLineItems: newLineItems,-->
<!--            newShippingMethods: newShippingMethods,-->
<!--            errors: errors,-->
<!--        });-->
<!--    };-->

<!--    session.oncancel = event => {-->
<!--        // Payment cancelled by WebKit-->
<!--    };-->

<!--    session.begin();-->
<!--}-->

<!--</script>-->

<!--<script>-->
<!--export default {-->
<!--    mounted() {-->
<!--        // Ejemplo de manejador de evento de clic en un botón-->

<!--        if (window.ApplePaySession) {-->
<!--            alert('Apple Pay es compatible')-->
<!--            // Verifica si el navegador es compatible con Apple Pay-->

<!--            const merchantIdentifier = 'merchant.placetopay-test'; // Reemplaza con tu identificador de comerciante de Apple Pay-->

<!--            const paymentRequest = {-->
<!--                countryCode: 'US', // Código de país-->
<!--                currencyCode: 'USD', // Código de moneda-->
<!--                supportedNetworks: ['visa', 'mastercard'], // Redes de tarjetas admitidas-->
<!--                merchantCapabilities: ['supports3DS'], // Capacidades del comerciante-->
<!--                total: {-->
<!--                    label: 'Total',-->
<!--                    amount: '10.00', // Monto total del pago-->
<!--                },-->
<!--            };-->

<!--            const session = new ApplePaySession(1, paymentRequest); // Crea una nueva sesión de Apple Pay-->

<!--            alert("session is: " + session)-->
<!--            session.onvalidatemerchant = (event) => {-->
<!--                // Lógica para validar el comerciante con Apple Pay-->

<!--                // Completa la validación del comerciante enviando el identificador de comerciante-->
<!--                session.completeMerchantValidation(merchantIdentifier);-->
<!--            };-->

<!--            session.onpaymentauthorized = (event) => {-->
<!--                // Lógica para procesar el pago autorizado-->

<!--                // Completa el pago autorizado enviando el pago al servidor-->
<!--                session.completePayment(ApplePaySession.STATUS_SUCCESS);-->
<!--            };-->

<!--            session.begin(); // Inicia la sesión de Apple Pay-->
<!--        } else {-->
<!--            // El navegador no es compatible con Apple Pay-->
<!--            console.log('Apple Pay no es compatible');-->
<!--        }-->
<!--    },-->
<!--}-->

<!--</script>-->

<script setup>

import {ref} from "vue";
import axios from "axios";

let idk = ref('vacio')

console.log()
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

function onApplePayButtonClicked() {
    console.log('Inicia ApplePay!')

    if (!ApplePaySession) {
        return;
    }

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

    // Create ApplePaySession
    const session = new ApplePaySession(10, simpleRequest);

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


        const merchantSession = await validateMerchant(event.validationURL);

        session.completeMerchantValidation(merchantSession);

        console.log('COMPLETAR VALIDACION DEL MERCHANT', merchantSession)


        // validateMerchant(event.validationURL, function (merchantSession) {
        //     session.completeMerchantValidation(merchantSession);
        //
        // });
    };


    session.onpaymentauthorized = function(event) {
        // Aquí es donde procesarías el pago. Por ejemplo, podrías enviar los detalles del pago a tu servidor para que sean procesados.
        // Asegúrate de que esta función devuelva una promesa que se resuelva cuando el pago se haya procesado correctamente.


        console.log('Nice Payment : ', event.payment)

        // let promise = processPayment(event.payment); // Asume que processPayment es tu función que procesa el pago

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

    /*
    session.onpaymentauthorized = event => {
        // Define ApplePayPaymentAuthorizationResult
        const result = {
            "status": ApplePaySession.STATUS_SUCCESS
        };
        session.completePayment(result);


    };

     */

    session.oncancel = event => {
        // Payment cancelled by WebKit
    };

    session.begin();

    // session.onpaymentmethodselected = event => {
    //     // Define ApplePayPaymentMethodUpdate based on the selected payment method.
    //     // No updates or errors are needed, pass an empty object.
    //     const update = {};
    //     session.completePaymentMethodSelection(update);
    // };

    // session.onshippingmethodselected = event => {
    //     // Define ApplePayShippingMethodUpdate based on the selected shipping method.
    //     // No updates or errors are needed, pass an empty object.
    //     const update = {};
    //     session.completeShippingMethodSelection(update);
    // };

    // session.onshippingcontactselected = event => {
    //     // Define ApplePayShippingContactUpdate based on the selected shipping contact.
    //     const update = {};
    //     session.completeShippingContactSelection(update);
    // };



    // session.oncouponcodechanged = event => {
    //     // Define ApplePayCouponCodeUpdate
    //     const newTotal = calculateNewTotal(event.couponCode);
    //     const newLineItems = calculateNewLineItems(event.couponCode);
    //     const newShippingMethods = calculateNewShippingMethods(event.couponCode);
    //     const errors = calculateErrors(event.couponCode);
    //
    //     session.completeCouponCodeChange({
    //         newTotal: newTotal,
    //         newLineItems: newLineItems,
    //         newShippingMethods: newShippingMethods,
    //         errors: errors,
    //     });
    // };


}
</script>

<template>
    <div>{{idk}}</div>

    <button @click="validateMerchant()">Validate merchant</button>
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
                <button @click="onApplePayButtonClicked()"
                        class="bg-black text-white py-2 px-4 rounded-lg hover:bg-gray-900 transition duration-200">Pagar
                    con Apple Pay
                </button>
                <button
                    class="bg-white border border-gray-300 py-2 px-4 rounded-lg hover:bg-gray-100 transition duration-200">
                    Pagar con otra forma de pago
                </button>
                <div class="flex bg-gray-300 rounded-md grid grid-cols-2 text-center gap-5">
                    <div class="font-bold">Windows y Linux</div>
                    <div class="font-bold">Apple</div>
                    <apple-pay-button class="buttonVisible" buttonstyle="black" type="plain" locale="en"></apple-pay-button>
                    <apple-pay-button @click="onApplePayButtonClicked()" buttonstyle="black" type="plain" locale="en"></apple-pay-button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.buttonVisible {
    display: block;
}
</style>
