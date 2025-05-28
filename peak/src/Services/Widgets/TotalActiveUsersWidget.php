<?php

namespace Qoraiche\Peak\Services\Widgets;

use Inertia\Inertia;
use Qoraiche\Peak\Services\Admin\AdminOverviewService;

class TotalActiveUsersWidget extends BaseWidget
{
    /**
     * @param AdminOverviewService $adminOverviewService
     */
    public function __construct(AdminOverviewService $adminOverviewService)
    {
        parent::__construct($adminOverviewService);
    }

    /**
     * @return float[]
     */
    public function data(): array
    {
        [$adminOverviewService] = $this->services;

        return [
            'totalActiveUsersCount' => Inertia::defer(fn() => number_format($adminOverviewService->getActiveUsersCount()), 'widgets'),
        ];
    }
}

