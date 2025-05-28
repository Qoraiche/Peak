<?php

namespace Qoraiche\Peak\Services\Widgets\Header;

use Qoraiche\Peak\Services\Widgets\BaseWidget;

class NotificationsHeaderWidget extends BaseWidget
{
    /**
     * @return float[]
     */
    public function data(): array
    {
        return [
            'unreadNotificationsCount' => current_user()->unreadNotifications()->count()
        ];
    }
}

