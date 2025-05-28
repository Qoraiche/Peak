<?php

namespace Qoraiche\Peak\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Qoraiche\Peak\Traits\HasExportableData;
use Qoraiche\Peak\Traits\HasFormattedDates;
use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    use HasFactory,
        HasExportableData,
        HasFormattedDates;
}
