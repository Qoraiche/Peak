<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Qoraiche\Peak\Traits\HasExportableData;
use Qoraiche\Peak\Traits\HasFormattedDates;

class License extends Model
{
    /** @use HasFactory<\Database\Factories\LicenseFactory> */
    use HasFactory, HasFormattedDates, HasExportableData;

    /** @var string[] */
    protected $fillable = [
        'user_id',
        'license_key',
        'user_id',
        'license_type',
        'plan',
        'purchase_date',
        'expires_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
