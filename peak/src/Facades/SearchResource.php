<?php

namespace Qoraiche\Peak\Facades;

use Illuminate\Support\Facades\Facade;
use Qoraiche\Peak\Services\SearchResourceManager;

/**
 * @method static SearchResourceManager all()
 */
class SearchResource extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'searchResource.manager';
    }
}
