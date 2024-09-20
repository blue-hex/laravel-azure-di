<?php

namespace BlueHex\LaravelAzureDI;

use Illuminate\Support\ServiceProvider;
use BlueHex\LaravelAzureDI\AzureDocumentIntelligence;

class LaravelAzureDIServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/azure-di.php', 'azure-di');

        $this->app->singleton('azure-di', function($app) {
            return new AzureDocumentIntelligence();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/azure-di.php' => config_path('azure-di.php'),
        ], 'config');

    }
}
