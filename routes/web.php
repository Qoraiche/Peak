<?php

/*
 * Add your routes here
 */

//use App\Http\Controllers\User\LicenseManagement;
use App\Http\Controllers\User\DownloadManagementController;
use App\Http\Controllers\User\Exports\LicenseExportController;
use App\Http\Controllers\User\LicenseManagementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


use Spatie\Activitylog\Models\Activity;

Route::get('/buy', function (Request $request) {
    return $request->user()->notify(new \App\Notifications\LicensePurchasedNotification());
});

Route::prefix(config('peak.paths.user'))->as('dashboard.')->middleware(config('peak.middlewares.user'))->group(function () {
    Route::prefix('licenses')->as('licenses.')->group(function () {
        Route::get('/', [LicenseManagementController::class, 'index'])->name('index');
        Route::get('/export', LicenseExportController::class)->name('export');
    });

    Route::prefix('downloads')->as('downloads.')->group(function () {
        Route::get('/', [DownloadManagementController::class, 'index'])->name('index');
    });
});

Route::prefix(config('peak.paths.admin'))->as('admin.')->middleware(config('peak.middlewares.admin'))->group(function () {
    /*Route::prefix('license')->group(function () {
        Route::get('/', function () {

        });
    });*/
});