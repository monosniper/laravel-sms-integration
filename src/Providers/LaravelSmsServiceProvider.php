<?php

namespace Monosniper\LaravelSms\Providers;


use Illuminate\Support\ServiceProvider;
use libphonenumber\PhoneNumberUtil;

class LaravelSmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->singleton(PhoneNumberUtil::class, fn() => PhoneNumberUtil::getInstance());
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/sms.php' => config_path('sms.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../config/sms.php', 'sms'
        );

        if ( ! defined('CURL_SSLVERSION_TLSv1_2')) { define('CURL_SSLVERSION_TLSv1_2', 6); }
    }
}