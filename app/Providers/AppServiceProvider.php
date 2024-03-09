<?php

namespace App\Providers;

use App\Domain\ApplePay\ApplePayLib\ApplePayClient;
use App\Domain\ApplePay\ApplePayLib\Contracts\ApplePayApi;
use App\Domain\ApplePay\ApplePayLib\DTO\ApplePayAuth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->singleton(ApplePayApi::class, fn() => new ApplePayClient([
                'auth' => ApplePayAuth::fromArray([
                    'cert' => storage_path('app/certificates/MERCHANT/merchant_id.pem'),
                    'sslKey' => storage_path('app/certificates/MERCHANT/rsakey.key'),
                    'sslKeyPassword' => config('services.apple_pay.ssl_key_password'),
                ]),
                'connect_timeout' => 5,
                'timeout' => 5,
        ]));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
