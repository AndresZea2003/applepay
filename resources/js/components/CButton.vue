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
                <button
                        class="bg-black text-white py-2 px-4 rounded-lg hover:bg-gray-900 transition duration-200">Pagar
                    con Apple Pay
                </button>
                <button
                    class="bg-white border border-gray-300 py-2 px-4 rounded-lg hover:bg-gray-100 transition duration-200">
                    Pagar con otra forma de pago
                </button>
                <div class="flex justify-center">
                    <apple-pay-button buttonstyle="black" type="plain" locale="en"></apple-pay-button>
                </div>
            </div>
        </div>
    </div>
</template>
