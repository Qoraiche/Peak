<?php

use Qoraiche\Peak\Http\Middleware\HandlePeakAdminInertiaRequests;

return [
    /*
    |--------------------------------------------------------------------------
    | Dashboard Paths
    |--------------------------------------------------------------------------
    | Define the paths for the admin and user dashboards.
    */

    'paths' => [
        'admin' => '/admin',
        'user' => '/dashboard',
        'contact' => '/contact',
    ],

    /*
    |--------------------------------------------------------------------------
    | Disabled Features
    |--------------------------------------------------------------------------
    | Define the disabled features
    | list of features:
    docs, pages, users, blog, roadmaps, changelogs, support_tickets, referrals, reports, docs, user_notifications, admin_notifications, languages, teams, api, user_manage_subscription, billing,
    */
    'disabled_features' => [
//        'users',
//        'exports',
//        'user_notifications',
//        'teams',
//        'settings',
//        'api',
//        'user_account_security',
//        'user_account_profile',
//        'user_dashboard'
    ],

    /*
     |--------------------------------------------------------------------------
     | Settings
     |--------------------------------------------------------------------------
     | Define the paths for the admin and user dashboards.
     */
    'settings' => [

        'show_made_by_peak' => true,
    ],


    /*
    |--------------------------------------------------------------------------
    | Role Permissions
    |--------------------------------------------------------------------------
    | Define role-based access control for dashboards.
    */
    'default_roles' => $defaultRoles = [
        'admin' => ['admin'],
        'user' => ['registered'],
        'all_users' => ['registered', 'admin'],
    ],

    'custom_permissions' => [
        /*'comments' => [
            'view-comments',
            'create-comments',
            'edit-comments',
            'delete-comments',
        ],*/
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware Configuration
    |--------------------------------------------------------------------------
    | Assign middleware to the respective dashboards for security.
    */
    'middlewares' => [
        'admin' => [
            'web',
            'auth:sanctum',
            HandlePeakAdminInertiaRequests::class,
            'can:access-admin'
        ],

        'user' => [
            'web',
            'auth:sanctum',
            'verified',
//            'role:' . Arr::join($defaultRoles['all_users'], '|')
        ],

        'front' => [
            'web',
            \Qoraiche\Peak\Http\Middleware\HandlePeakInertiaRequests::class
        ],

        'settings' => [
            'can:edit-settings'
        ],

        'webhooks' => ['web'],
    ],

];
