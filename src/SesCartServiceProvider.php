<?php

namespace AbstractEverything\SesCart;

use Illuminate\Support\ServiceProvider;

class SesCartServiceProvider extends ServiceProvider
{
    public function register()
    {
        // ...
    }

    public function boot()
    {
        $this->publishConfig();
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__.'/config/sescart.php' => config_path('sescart.php')
        ], 'config');
    }
}