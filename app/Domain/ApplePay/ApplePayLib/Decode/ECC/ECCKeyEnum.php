<?php

namespace App\Domain\ApplePay\ApplePayLib\Decode\ECC;

enum ECCKeyEnum: string
{
    case PUBLIC_KEY = 'PUBLIC KEY';

    case EC_PRIVATE_KEY = 'EC PRIVATE KEY';

}
