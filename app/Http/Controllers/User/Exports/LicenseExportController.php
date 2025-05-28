<?php

namespace App\Http\Controllers\User\Exports;

use App\Models\License;
use Qoraiche\Peak\Http\Controllers\Admin\Exports\ExportController;

class LicenseExportController extends ExportController
{
    /** @var string */
    public string $classModel = License::class;

    /** @var string */
    public string $tableName = 'licenses';
}
