<?php

namespace Qoraiche\Peak\Services\Widgets\Header;

use Qoraiche\Peak\Services\Widgets\BaseWidget;

class VisitWebsiteHeaderWidget extends BaseWidget
{
    /**
     * @return float[]
     */
    public function data(): array
    {
        return [
            'url' => config('app.url')
        ];
    }
}

