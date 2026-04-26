<?php

namespace GSMUI\Facades;

use Illuminate\Support\Facades\Facade;

class GSMUI extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'gsmui';
    }
}