<?php

namespace Qoraiche\Peak\Http\Controllers\Admin\Exports;

use App\Models\Team;

class TeamExportController extends ExportController
{
    /** @var string */
    public string $classModel = Team::class;

    /** @var string */
    public string $tableName = 'teams';
}
