<?php

namespace Qoraiche\Peak\Http\Controllers\Admin\Exports;

use Qoraiche\Peak\Models\Permission;

class PermissionExportController extends ExportController
{
    /** @var string */
    public string $classModel = Permission::class;

    /** @var string */
    public string $tableName = 'permissions';
}
