<?php

namespace BlueHex\LaravelAzureDI\Facades;

use Illuminate\Support\Facades\Facade;

class AzureDI extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'azure-di';
    }

    public static function make()
    {
        return app('azure-di');
    }
}
