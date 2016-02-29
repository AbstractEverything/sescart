<?php

namespace AbstractEverything\SesCart\Facades;

use Illuminate\Support\Facades\Facade;

class SesCart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sescart';
    }
}