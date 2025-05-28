<?php

namespace Qoraiche\Peak\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Qoraiche\Peak\Traits\HasExportableData;
use Qoraiche\Peak\Traits\HasFormattedDates;

class Export extends Model
{
    use HasFactory,
        HasFormattedDates,
        HasExportableData;

    const PENDING_STATUS = 'pending';
    const COMPLETED_STATUS = 'completed';
    const FAILED_STATUS = 'failed';

    /** @var string[] */
    protected $fillable = [
        'user_id',
        'columns',
        'ids',
        'name',
        'model',
        'locale',
        'file_name',
        'file_path',
        'format',
        'status'
    ];

    /** @var string[] */
    protected $casts = [
        'columns' => 'array',
        'ids' => 'array',
    ];

    protected $appends = [
        'download_url'
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string
     */
    public function getDownloadUrlAttribute()
    {
        return Storage::url($this->file_path);
    }
}
