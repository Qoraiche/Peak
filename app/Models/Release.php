<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Qoraiche\Peak\Traits\HasExportableData;

class Release extends Model
{
    /** @use HasFactory<\Database\Factories\ReleaseFactory> */
    use HasFactory, HasExportableData;

    /** @var string[] */
    protected $fillable = [
        'version',
        'title',
        'description',
        'release_date',
        'download_url',
    ];
}
