<?php

namespace Qoraiche\Peak\Facades;

use Illuminate\Support\Facades\Facade;

class Card extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'card.manager';
    }
}
