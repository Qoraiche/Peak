<?php

namespace Qoraiche\Peak\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Qoraiche\Peak\Models\Scopes\OrderByOrderColumn;
use Qoraiche\Peak\Traits\HasExportableData;
use Qoraiche\Peak\Traits\HasFormattedDates;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Language extends Model implements HasMedia, Sortable
{
    use HasFactory;
    use InteractsWithMedia;
    use SortableTrait;
    use HasExportableData;
    use HasFormattedDates;

    const ID_COLUMN_NAME = 'id';
    const NAME_COLUMN_NAME = 'name';
    const CODE_COLUMN_NAME = 'code';
    const DIRECTION_COLUMN_NAME = 'direction';
    const DEFAULT_COLUMN_NAME = 'default';
    const ACTIVE_COLUMN_NAME = 'active';
    const FLAG_EMOJI_COLUMN_NAME = 'flag_emoji';
    const ORDER_COLUMN_NAME = 'order_column';
    
    /**
     * @var array
     */
    public $sortable = [
        'order_column_name' => 'order_column',
        'sort_when_creating' => true,
    ];
    /**
     * @var string[]
     */
    protected $fillable = [
        self::NAME_COLUMN_NAME,
        self::DIRECTION_COLUMN_NAME,
        self::CODE_COLUMN_NAME,
        self::DEFAULT_COLUMN_NAME,
        self::ACTIVE_COLUMN_NAME,
        self::FLAG_EMOJI_COLUMN_NAME,
        self::FLAG_EMOJI_COLUMN_NAME,
        self::ORDER_COLUMN_NAME
    ];

    /**
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new OrderByOrderColumn);

        static::saved(function ($language) {
            Cache::forget('app_languages');
            Cache::forget("language_collection_{$language->code}");
        });

        static::deleted(function ($language) {
            Cache::forget('app_languages');
            Cache::forget("language_collection_{$language->code}");
        });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDefault($query)
    {
        return $query->where(self::DEFAULT_COLUMN_NAME, true);
    }
}
