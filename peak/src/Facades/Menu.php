<?php

namespace Qoraiche\Peak\Facades;

use Illuminate\Support\Facades\Facade;

class Menu extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'menu.manager';
    }
}
