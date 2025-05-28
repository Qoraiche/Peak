<?php

namespace Qoraiche\Peak\Http\Controllers\Admin\Exports;

use Qoraiche\Peak\Models\Role;

class RoleExportController extends ExportController
{
    /** @var string */
    public string $classModel = Role::class;

    /** @var string */
    public string $tableName = 'roles';
}
