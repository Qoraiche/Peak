<?php

namespace Qoraiche\Peak\Services\Widgets\Header;

use Qoraiche\Peak\Facades\Menu;
use Qoraiche\Peak\Services\Widgets\BaseWidget;

class QuickNavigationHeaderWidget extends BaseWidget
{
    /**
     * @return float[]
     */
    public function data(): array
    {
        return [
            'navigationMenu' => Menu::get('admin_quick_navigation_menu')
        ];
    }
}

