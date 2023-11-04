<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://applepay.cdn-apple.com/jsapi/v1/apple-pay-sdk.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Apple Pay</title>
</head>
<body>
<div id="app">
    <form action="{{ route('apple') }}" method="post">
        @csrf
        <input type="text" name="validationUrl" value="https://apple-pay-gateway.apple.com/paymentservices/startSession" hidden="">
        <button type="submit">Go</button>
    </form>
    <apple-pay></apple-pay>
</div>
</body>
</html>
