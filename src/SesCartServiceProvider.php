<?php

namespace AbstractEverything\SesCart;

use Illuminate\Support\ServiceProvider;

class SesCartServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('sescart', function($app)
        {
            return new \AbstractEverything\SesCart\Cart\CartManager($app['session']);
        });
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