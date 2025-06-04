<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\RoutePath;
use Qoraiche\Peak\Http\Controllers\Admin\AdminOverviewController;
use Qoraiche\Peak\Http\Controllers\Admin\Api\SearchUserController;
use Qoraiche\Peak\Http\Controllers\Admin\DeleteUserProfilePhoto;
use Qoraiche\Peak\Http\Controllers\Admin\Exports\ExportManagementController;
use Qoraiche\Peak\Http\Controllers\Admin\Exports\PermissionExportController;
use Qoraiche\Peak\Http\Controllers\Admin\Exports\RoleExportController;
use Qoraiche\Peak\Http\Controllers\Admin\Exports\TeamExportController;
use Qoraiche\Peak\Http\Controllers\Admin\Exports\UserExportController;
use Qoraiche\Peak\Http\Controllers\Admin\NoteManagementController;
use Qoraiche\Peak\Http\Controllers\Admin\PermissionManagementController;
use Qoraiche\Peak\Http\Controllers\Admin\RoleManagementController;
use Qoraiche\Peak\Http\Controllers\Admin\Settings\Frontend\CustomCodeSettingsController;
use Qoraiche\Peak\Http\Controllers\Admin\Settings\Frontend\ThemeSettingsController;
use Qoraiche\Peak\Http\Controllers\Admin\Settings\System\CacheController;
use Qoraiche\Peak\Http\Controllers\Admin\Settings\System\LogController;
use Qoraiche\Peak\Http\Controllers\Admin\Settings\Website\WebsiteFileStorageSettingsController;
use Qoraiche\Peak\Http\Controllers\Admin\Settings\Website\WebsiteGeneralSettingsController;
use Qoraiche\Peak\Http\Controllers\Admin\Settings\Website\WebsiteMailSettingsController;
use Qoraiche\Peak\Http\Controllers\Admin\TeamManagementController;
use Qoraiche\Peak\Http\Controllers\Admin\UpdateUserPassword;
use Qoraiche\Peak\Http\Controllers\Admin\UpdateUserProfilePhoto;
use Qoraiche\Peak\Http\Controllers\Admin\UserManagementController;
use Qoraiche\Peak\Http\Controllers\Frontend\AboutController;
use Qoraiche\Peak\Http\Controllers\Frontend\FrontController;
use Qoraiche\Peak\Http\Controllers\Frontend\ContactController;
use Qoraiche\Peak\Http\Controllers\User\ApiTokenController;
use Qoraiche\Peak\Http\Controllers\User\DashboardController;
use Qoraiche\Peak\Http\Controllers\User\NotificationController;
use Qoraiche\Peak\Http\Controllers\User\ProfileController;
use Qoraiche\Peak\Http\Controllers\User\SecurityController;
use Spatie\Robots\RobotsTxt;

/**
 * Frontend
 */
Route::middleware(config('peak.middlewares.front'))->group(callback: function () {
    Route::get('/', action: [FrontController::class, 'index'])->name('home');
    Route::get(config('peak.paths.about', 'about'), [AboutController::class, 'index'])->name('about.index');
    Route::get(config('peak.paths.contact', 'contact'), [ContactController::class, 'index'])->name('contact.index');
});

/**
 * User
 */
Route::prefix(config('peak.paths.user', 'dashboard'))
    ->name('dashboard.')
    ->middleware(config('peak.middlewares.user'))->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('index');

        Route::prefix('account')->name('account.')->group(function () {

            Route::prefix('notifications')->name('notifications.')->group(function () {
                Route::get('/', [NotificationController::class, 'index'])->name('index');
                Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
                Route::get('/read', [NotificationController::class, 'markAsRead'])->name('read');
            });

            Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
            Route::get('/security', [SecurityController::class, 'show'])->name('security');
            Route::get('/api', [ApiTokenController::class, 'index'])->name('api');

            Route::prefix('api-tokens')->name('api-tokens.')->group(function () {
                Route::get('/', [ApiTokenController::class, 'index'])->name('index');
                Route::post('/', [ApiTokenController::class, 'store'])->name('store');
                Route::put('/{token}', [ApiTokenController::class, 'update'])->name('update');
                Route::delete('/{token}', [ApiTokenController::class, 'destroy'])->name('destroy');
            });
        });
    });

/**
 * Admin
 */
Route::prefix(config('peak.paths.admin', 'admin'))
    ->name('admin.')
    ->middleware(config('peak.middlewares.admin'))
    ->group(function () {

        Route::get('/', [AdminOverviewController::class, 'index'])->name('dashboard');

        Route::prefix('settings')->middleware(config('peak.middlewares.settings'))->name('settings.')->group(function () {

            /* WEBSITE */
            Route::get('/website/general', [WebsiteGeneralSettingsController::class, 'settings'])->name('website.general');
            Route::put('/website/general', [WebsiteGeneralSettingsController::class, 'update'])->name('website.general.update');

            Route::get('/website/mail', [WebsiteMailSettingsController::class, 'settings'])->name('website.mail');
            Route::put('/website/mail', [WebsiteMailSettingsController::class, 'update'])->name('website.mail.update');

            Route::get('/website/file-storage', [WebsiteFileStorageSettingsController::class, 'settings'])->name('website.file-storage');
            Route::put('/website/file-storage', [WebsiteFileStorageSettingsController::class, 'update'])->name('website.file-storage.update');

            Route::prefix('frontend')->name('frontend.')->middleware(['can:edit-frontend-settings'])->group(function () {

                Route::get('theme', [ThemeSettingsController::class, 'settings'])->name('theme');
                Route::put('theme', [ThemeSettingsController::class, 'update'])->name('theme.update');

                Route::prefix('custom-code')->name('custom-code.')->group(function () {
                    Route::get('/', CustomCodeSettingsController::class)->name('edit');
                    Route::put('/css', [CustomCodeSettingsController::class, 'updateCss'])->name('css.update');
                    Route::put('/js', [CustomCodeSettingsController::class, 'updateJs'])->name('js.update');
                });
            });

            Route::prefix('system')->name('system.')->group(function () {
                Route::prefix('cache')->name('cache.')->middleware(['can:clear-cache'])->group(function () {
                    Route::get('/', CacheController::class)->name('edit');
                    Route::delete('/clear', [CacheController::class, 'clear'])->name('clear');
                });

                Route::prefix('log')->name('log.')->middleware(['can:view-app-logs'])->group(function () {
                    Route::get('/', LogController::class)->name('edit');
                });
            });
        });

        Route::prefix('/api')->name('api.')->group(function () {
            Route::get('search-users', SearchUserController::class)->name('search-users');
        });

        Route::get('/', [AdminOverviewController::class, 'index'])->name('dashboard');

        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [\Qoraiche\Peak\Http\Controllers\Admin\NotificationController::class, 'index'])->name('index');
            Route::get('/{id}', [\Qoraiche\Peak\Http\Controllers\Admin\NotificationController::class, 'show'])->name('show');
            Route::get('/read', [\Qoraiche\Peak\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('read');
        });

        // Users management
        Route::prefix('user-management')->name('user-management.')->group(function () {

            // users
            Route::put('users/{user}/password', UpdateUserPassword::class)->name('users.update.password');
            Route::put('users/{user}/photo', UpdateUserProfilePhoto::class)->name('users.update.photo');
            Route::delete('users/{user}/photo', DeleteUserProfilePhoto::class)->name('users.delete.photo');
            Route::delete('users', [UserManagementController::class, 'bulkDestroy'])->name('users.bulk-destroy');
            Route::resource('users', UserManagementController::class);

            //user notes
            Route::delete('notes/{note}', [NoteManagementController::class, 'destroy'])->name('notes.destroy');
            Route::put('notes/{note}', [NoteManagementController::class, 'update'])->name('notes.update');
            Route::post('notes/{user}', [NoteManagementController::class, 'store'])->name('notes.store');

            // roles
            Route::delete('roles', [RoleManagementController::class, 'bulkDestroy'])->name('roles.bulk-destroy');
            Route::resource('roles', RoleManagementController::class);

            // permissions
            Route::delete('permissions', [PermissionManagementController::class, 'bulkDestroy'])->name('permissions.bulk-destroy');
            Route::resource('permissions', PermissionManagementController::class);

            // teams
            Route::delete('teams', [TeamManagementController::class, 'bulkDestroy'])->name('teams.bulk-destroy');
            Route::resource('teams', TeamManagementController::class);

            // exports
            Route::prefix('export')->name('export.')->group(function () {
                Route::get('users', UserExportController::class)->name('users');
                Route::get('roles', RoleExportController::class)->name('roles');
                Route::get('teams', TeamExportController::class)->name('teams');
                Route::get('permissions', PermissionExportController::class)->name('permissions');
            });
        });

        Route::prefix('exports')->name('exports.')->group(function () {
            Route::get('/', [ExportManagementController::class, 'index'])->name('index');
            Route::delete('items', [ExportManagementController::class, 'bulkDestroy'])->name('items.bulk-delete');
            Route::delete('/{export}', [ExportManagementController::class, 'destroy'])->name('delete');
        });
    });

Route::get('/robots.txt', function () {
    $adminPath = trim(config('peak.paths.admin', 'admin'), '/');

    $robots = RobotsTxt::create()
        ->addUserAgent('*')
        ->addDisallow("/$adminPath")
        ->addDisallow(RoutePath::for('login', '/login'))
        ->addDisallow(RoutePath::for('register', '/register'));

    return response((string)$robots, 200)
        ->header('Content-Type', 'text/plain');
});