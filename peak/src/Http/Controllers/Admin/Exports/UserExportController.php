<?php

namespace Qoraiche\Peak\Http\Controllers\Admin\Exports;

use Qoraiche\Peak\Models\User;

class UserExportController extends ExportController
{
    /** @var string */
    public string $classModel = User::class;

    /** @var string */
    public string $tableName = 'users';
}
