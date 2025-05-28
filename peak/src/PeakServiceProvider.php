<?php

namespace Qoraiche\Peak;

use App\Console\Commands\ImportTranslationsFromJson;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;
use Opcodes\LogViewer\Facades\LogViewer;
use Qoraiche\Peak\Console\Commands\CreateRole;
use Qoraiche\Peak\Console\Commands\CreateUser;
use Qoraiche\Peak\Console\Commands\GenerateSitemap;
use Qoraiche\Peak\Facades\Card;
use Qoraiche\Peak\Facades\Menu;
use Qoraiche\Peak\Facades\SearchResource;
use Qoraiche\Peak\Facades\Widget;
use Qoraiche\Peak\Http\Middleware\CheckAuthSecurity;
use Qoraiche\Peak\Http\Middleware\CheckDisabledFeatures;
use Qoraiche\Peak\Http\Middleware\ForceHttps;
use Qoraiche\Peak\Http\Middleware\HandleDemo;
use Qoraiche\Peak\Http\Middleware\HandlePeakInertiaRequests;
use Qoraiche\Peak\Http\Middleware\SetLocale;
use Qoraiche\Peak\Http\Middleware\SetTheme;
use Qoraiche\Peak\Http\Responses\CustomLoginResponse;
use Qoraiche\Peak\Services\CardManager;
use Qoraiche\Peak\Services\Cards\RecentUsersCard;
use Qoraiche\Peak\Services\MenuManager;
use Qoraiche\Peak\Services\SearchResourceManager;
use Qoraiche\Peak\Services\WidgetManager;
use Qoraiche\Peak\Services\Widgets\Header\NotificationsHeaderWidget;
use Qoraiche\Peak\Services\Widgets\Header\QuickNavigationHeaderWidget;
use Qoraiche\Peak\Services\Widgets\Header\ToggleFullScreenHeaderWidget;
use Qoraiche\Peak\Services\Widgets\Header\VisitWebsiteHeaderWidget;
use Qoraiche\Peak\Services\Widgets\TotalActiveUsersWidget;
use Qoraiche\Peak\Support\PeakFeatures;

class PeakServiceProvider extends ServiceProvider
{
    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function boot()
    {
        Model::automaticallyEagerLoadRelationships();

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config/peak.php' => config_path('peak.php'),
        ], 'peak-config');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'peak-migrations');

        $this->loadJsonTranslationsFrom(__DIR__ . '/../lang');

        $this->canViewLogs();
        $this->registerCommands();
        $this->registerMiddlewares();
        $this->loadViews();
        $this->loadWidgets();
        $this->loadMenus();
        $this->loadCards();
        $this->loadBreadcrumbs();
        $this->loadSearchResources();
    }

    /**
     * @return void
     */
    private function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateUser::class,
                CreateRole::class,
                GenerateSitemap::class,
                ImportTranslationsFromJson::class
            ]);
        }
    }

    /**
     * @return void
     */
    private function registerMiddlewares()
    {
        $this->app->booted(function () {

            $router = $this->app->make(Router::class);

            // Register multiple middlewares to a specific group (e.g., 'web')
            $middlewares = [
                CheckAuthSecurity::class,
                CheckDisabledFeatures::class,
                HandlePeakInertiaRequests::class,
                HandleDemo::class,
                SetLocale::class,
                SetTheme::class,
                ForceHttps::class
            ];

            foreach ($middlewares as $middleware) {
                $router->pushMiddlewareToGroup('web', $middleware);
            }
        });
    }

    /**
     * @return void
     */
    private function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'peak');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/peak'),
        ]);
    }

    /**
     * @return void
     */
    private function loadWidgets()
    {
        $widgets = [
            [
                'slug' => 'quick-navigation-header-widget',
                'title' => __('Quick Navigation'),
                'icon' => 'Menu',
                'component' => QuickNavigationHeaderWidget::class,
                'order' => 1,
                'group' => 'header',
            ],
            [
                'slug' => 'toggle-fullscreen-header-widget',
                'title' => __('Fullscreen Mode'),
                'icon' => 'Maximize',
                'component' => ToggleFullScreenHeaderWidget::class,
                'order' => 3,
                'group' => 'header',
            ],
            [
                'slug' => 'visit-website-header-widget',
                'title' => __('Visit Website'),
                'icon' => 'ExternalLink',
                'component' => VisitWebsiteHeaderWidget::class,
                'order' => 5,
                'group' => 'header',
            ]
        ];

        if (PeakFeatures::enabled('admin_notifications')) {
            $widgets[] = [
                'slug' => 'notifications-header-widget',
                'title' => __('Notifications'),
                'icon' => 'Bell',
                'component' => NotificationsHeaderWidget::class,
                'order' => 4,
                'group' => 'header',
            ];
        }


        if (PeakFeatures::enabled('users')) {
            $widgets[] = [
                'slug' => 'active-users-count',
                'title' => __('Active Users'),
                'icon' => 'Users',
                'component' => TotalActiveUsersWidget::class,
                'order' => 3,
                'group' => 'default'
            ];
        }

        Widget::registerMany($widgets);
    }

    /**
     * @return void
     */
    private function loadMenus()
    {
        /**
         * Quick navigation header widget menu
         */
        $quickNavigationMenu = [
            [
                'slug' => 'dashboard',
                'title' => __('Dashboard'),
                'route' => 'admin.dashboard',
                'target' => 'self',
                'icon' => 'House'
            ]
        ];

        if (PeakFeatures::enabled('users')) {
            $quickNavigationMenu[] = [
                'slug' => 'quick-link-users',
                'title' => __('Users'),
                'route' => 'admin.user-management.users.index',
                'target' => 'self',
                'icon' => 'Users'
            ];
        }

        if (PeakFeatures::enabled('users')) {
            $quickNavigationMenu[] = [
                'slug' => 'quick-link-roles',
                'title' => __('Roles'),
                'route' => 'admin.user-management.roles.index',
                'target' => 'self',
                'icon' => 'ShieldUser'
            ];
        }

        if (PeakFeatures::enabled('settings')) {
            $quickNavigationMenu[] = [
                'slug' => 'quick-link-general-settings',
                'title' => __('General Settings'),
                'route' => 'admin.settings.website.general',
                'target' => 'self',
                'icon' => 'Wrench'
            ];
        }

        Menu::register('admin_quick_navigation_menu', $quickNavigationMenu);

        /**
         * Admin user profile menu
         */

        $userMenu = [];

        if (PeakFeatures::enabled('user_dashboard')) {
            $userMenu[] = [
                'slug' => 'menu-user-dashboard',
                'title' => __('User Dashboard'),
                'target' => 'self',
                'icon' => 'LayoutDashboard',
                'route' => 'dashboard.index'
            ];
        }

        if (PeakFeatures::enabled('user_account_profile')) {
            $userMenu[] = [
                'slug' => 'menu-user-profile',
                'title' => __('My Profile'),
                'target' => 'self',
                'icon' => 'Users',
                'route' => 'dashboard.account.profile'
            ];
        }

        if (PeakFeatures::enabled('user_account_security')) {
            $userMenu[] = [
                'slug' => 'menu-user-change-password',
                'title' => __('Change Password'),
                'target' => 'self',
                'icon' => 'KeyRound',
                'route' => 'dashboard.account.security'
            ];
        }

        if (PeakFeatures::enabled('user_account_security')) {
            $userMenu[] = [
                'slug' => 'menu-user-2fa',
                'title' => __('Two-Factor Authentication'),
                'target' => 'self',
                'icon' => 'ShieldCheck',
                'route' => 'dashboard.account.security'
            ];
        }

        if (PeakFeatures::enabled('settings')) {
            $userMenu[] = [
                'slug' => 'menu-system-logs',
                'title' => __('Application Logs'),
                'target' => 'self',
                'icon' => 'FileText',
                'route' => 'admin.settings.system.log.edit'
            ];

            $userMenu[] = [
                'slug' => 'menu-settings-clear-cache',
                'title' => __('Clear Cache'),
                'target' => 'self',
                'icon' => 'RefreshCw',
                'route' => 'admin.settings.system.cache.edit'
            ];
        }

        Menu::register('admin_user_menu', $userMenu);

        $mainMenu = [
            [
                'slug' => 'admin.index',
                'title' => __('Dashboard'),
                'route' => 'admin.dashboard',
                'icon' => 'House'
            ],
        ];

        if (PeakFeatures::enabled('users')) {

            $usersChildren = [
                [
                    'slug' => 'all-users',
                    'title' => __('All Users'),
                    'route' => 'admin.user-management.users.index',
                ],
                [
                    'slug' => 'new-user',
                    'title' => __('New User'),
                    'route' => 'admin.user-management.users.create',
                ],
                [
                    'slug' => 'roles',
                    'title' => __('Roles'),
                    'route' => 'admin.user-management.roles.index',
                ],
                [
                    'slug' => 'permissions',
                    'title' => __('Permissions'),
                    'route' => 'admin.user-management.permissions.index',
                ]
            ];

            if (PeakFeatures::enabled('teams')) {
                $usersChildren[] = [
                    'slug' => 'teams',
                    'title' => __('Teams'),
                    'route' => 'admin.user-management.teams.index',
                ];
            }

            $mainMenu[] = [
                'slug' => 'users',
                'title' => 'Users',
                'icon' => 'Users',
                'children' => $usersChildren,
            ];
        }

        if (PeakFeatures::enabled('settings')) {
            $mainMenu[] = [
                'slug' => 'settings-front',
                'title' => __('Frontend'),
                'icon' => 'Palette',
                'children' => [
                    [
                        'slug' => 'settings-front-theme',
                        'title' => __('Theme'),
                        'route' => 'admin.settings.frontend.theme',
                    ],
                    [
                        'slug' => 'settings-front-custom-code',
                        'title' => __('Custom Code'),
                        'route' => 'admin.settings.frontend.custom-code.edit',
                    ]
                ]
            ];
        }

        if (PeakFeatures::enabled('settings')) {
            $mainMenu[] = [
                'slug' => 'settings',
                'title' => 'Settings',
                'icon' => 'Wrench',
                'children' => [
                    [
                        'slug' => 'settings-general',
                        'title' => __('General'),
                        'route' => 'admin.settings.website.general',
                    ],
                    [
                        'slug' => 'settings-exports',
                        'title' => __('Exports'),
                        'route' => 'admin.exports.index',
                    ],
                    [
                        'slug' => 'settings-mail',
                        'title' => __('Mail'),
                        'route' => 'admin.settings.website.mail',
                    ],
                    [
                        'slug' => 'settings-file-storage',
                        'title' => __('File Storage'),
                        'route' => 'admin.settings.website.file-storage',
                    ],
                ],
                [
                    'slug' => 'system',
                    'title' => __('System'),
                    'icon' => 'Server',
                    'children' => [
                        [
                            'slug' => 'system-cache',
                            'title' => __('Cache'),
                            'route' => 'admin.system.cache.edit',
                        ],
                        [
                            'slug' => 'system-log',
                            'title' => __('Log'),
                            'route' => 'admin.system.log.edit',
                        ],
                    ],
                ]
            ];
        }

        if (PeakFeatures::enabled('settings')) {
            $mainMenu[] = [
                'slug' => 'settings-system',
                'title' => __('System'),
                'icon' => 'Server',
                'children' => [
                    [
                        'slug' => 'settings-system-clear-cache',
                        'title' => __('Clear Cache'),
                        'route' => 'admin.settings.system.cache.edit',
                    ],
                    [
                        'slug' => 'settings-system-application-logs',
                        'title' => __('Application Logs'),
                        'route' => 'admin.settings.system.log.edit'
                    ],
                ]
            ];
        }

        /**
         * Admin Sidebar Registration
         */
        Menu::register('admin_main_menu', $mainMenu);
    }

    /**
     * @return void
     */
    public function register()
    {
        // Merge Configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/peak.php', 'peak',
        );

        $this->app->singleton(LoginResponse::class, CustomLoginResponse::class);

        $this->registerMenuManager();
        $this->registerCardManager();
        $this->registerWidgetManager();
        $this->registerSearchResourceManager();
    }

    /**
     * @return void
     */
    private function registerMenuManager()
    {
        $this->app->singleton('menu.manager', function () {
            return new MenuManager();
        });
    }

    /**
     * @return void
     */
    private function registerCardManager()
    {
        $this->app->singleton('card.manager', function () {
            return new CardManager();
        });
    }

    /**
     * @return void
     */
    private function registerWidgetManager()
    {
        $this->app->singleton('widget.manager', function () {
            return new WidgetManager();
        });
    }

    /**
     * @return void
     */
    private function registerSearchResourceManager()
    {
        $this->app->singleton('searchResource.manager', function () {
            return new SearchResourceManager();
        });
    }

    /**
     * @return void
     */
    private function loadCards()
    {
        $cards = [];

        if (PeakFeatures::enabled('users')) {
            $cards[] = [
                'slug' => 'recent-users-card',
                'title' => __('New User Signups'),
                'description' => __('Overview of users who have recently registered.'),
                'component' => RecentUsersCard::class,
                'viewMoreRouteName' => 'admin.user-management.users.index'
            ];
        }

        Card::registerMany($cards);
    }

    /**
     * @return void
     */
    private function loadBreadcrumbs()
    {
        // Exports
        Breadcrumbs::for('admin.exports.index', function (BreadcrumbTrail $trail) {
            $trail->push(__('Exports'), route('admin.exports.index'));
        });

        // Notifications
        Breadcrumbs::for('admin.notifications.index', function (BreadcrumbTrail $trail) {
            $trail->push(__('Notifications'), route('admin.notifications.index'));
        });

        // Users
        Breadcrumbs::for('admin.user-management.users.index', function (BreadcrumbTrail $trail) {
            $trail->push(__('Users'), route('admin.user-management.users.index'));
        });

        Breadcrumbs::for('admin.user-management.users.create', function (BreadcrumbTrail $trail) {
            $trail->parent('admin.user-management.users.index');
            $trail->push(__('Create'));
        });

        // Users -> Edit
        Breadcrumbs::for('admin.user-management.users.edit', function (BreadcrumbTrail $trail, $user) {
            $user = Helpers::getUserAuthModelInstance()->findOrFail($user);

            $trail->parent('admin.user-management.users.index');
            $trail->push($user->name ?? $user->email, route('admin.user-management.users.edit', $user->id));
        });

        // Users -> Roles
        Breadcrumbs::for('admin.user-management.roles.index', function (BreadcrumbTrail $trail) {
            $trail->push(__('Roles'), route('admin.user-management.roles.index'));
        });

        // Users -> Permissions
        Breadcrumbs::for('admin.user-management.permissions.index', function (BreadcrumbTrail $trail) {
            $trail->push(__('Permissions'), route('admin.user-management.permissions.index'));
        });
    }

    /**
     * @return void
     */
    private function loadSearchResources()
    {
        if (PeakFeatures::enabled('users')) {
            SearchResource::register(slug: 'users', title: __('Users'), resourceRoute: 'admin.user-management.users.index', colorClass: 'bg-teal-500', icon: 'Users');
        }

        if (PeakFeatures::enabled('exports')) {
            SearchResource::register(slug: 'exports', title: __('Exports'), resourceRoute: 'admin.exports.index', colorClass: 'bg-orange-500', icon: 'Upload');
        }
    }

    /**
     * @return void
     */
    private function canViewLogs()
    {
        LogViewer::auth(function ($request) {
            return $request->user() &&
                $request->user()->hasAnyRole(config('peak.default_roles.admin')) &&
                $request->user()->hasPermissionTo('view-app-logs');
        });
    }
}
