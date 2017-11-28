<?php

namespace Dartui\SmsGateway;

use Dartui\SmsGateway\Manager;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Manager::class, function ($app) {
            $config = $app->make('config')->get('services.sms-gateway');

            return new Manager($config);
        });
    }
}
