<?php

namespace Qoraiche\Peak\Support;

class PeakFeatures
{
    /**
     * @param string $feature
     * @return bool
     */
    public static function enabled(string $feature): bool
    {
        return !in_array($feature, config('peak.disabled_features', []));
    }

    /**
     * @param string $feature
     * @return bool
     */
    public static function disabled(string $feature): bool
    {
        return in_array($feature, config('peak.disabled_features', []));
    }

    /**
     * @param string $feature
     * @return string|array|null
     */
    public static function pattern(string $feature): string|array|null
    {
        return static::featureRoutes()[$feature] ?? null;
    }

    /**
     * @return array<string, string>
     */
    public static function featureRoutes(): array
    {
        return [
            'docs' => 'admin.docs.*',
            'pages' => 'admin.pages.*',
            'users' => 'admin.user-management.*',
            'blog' => 'admin.blog.*',
            'roadmaps' => 'admin.roadmaps.*',
            'changelogs' => 'admin.changelogs.*',
            'support_tickets' => 'admin.support.*',
            'exports' => 'admin.exports.*',
            'referrals' => 'admin.referrals.*',
            'reports' => 'admin.reports.*',
            'user_notifications' => 'dashboard.account.notifications.*',
            'admin_notifications' => 'admin.notifications.*',
            'languages' => 'admin.languages.index',
            'teams' => 'teams.*',
            'settings' => 'admin.settings.*',
            'api' => 'api-tokens.*',
            'user_account_security' => 'dashboard.account.security',
            'user_account_profile' => 'dashboard.account.profile',
            'user_account_manage_subscription' => 'dashboard.account.subscription',
            'billing' => [
                'dashboard.account.billing',
                'admin.finance.*',
            ],
            // Add more features here
        ];
    }
}