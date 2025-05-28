<?php

namespace Qoraiche\Peak\Facades;

use Illuminate\Support\Facades\Facade;

class Widget extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'widget.manager';
    }
}
