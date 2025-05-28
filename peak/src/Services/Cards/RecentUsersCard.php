<?php

namespace Qoraiche\Peak\Services\Cards;


use Inertia\Inertia;
use Qoraiche\Peak\Services\Admin\AdminOverviewService;

class RecentUsersCard extends BaseCard
{
    /**
     * @param AdminOverviewService $adminOverviewService
     */
    public function __construct(AdminOverviewService $adminOverviewService)
    {
        parent::__construct($adminOverviewService);
    }

    /** @inheritDoc */
    public function data(): array
    {
        [$adminOverviewService] = $this->services;

        return [
            'recentUsers' => Inertia::defer(fn() => $adminOverviewService->getRecentRegisteredUsers(), 'recentCards'),
        ];
    }
}
