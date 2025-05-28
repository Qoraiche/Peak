<?php

namespace Qoraiche\Peak\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Qoraiche\Peak\Traits\HasFormattedDates;

class UserNote extends Model
{
    use HasFactory,
        HasFormattedDates;

    const NOTE_COLUMN_NAME = 'note';
    const USER_ID_COLUMN_NAME = 'user_id';
    const POSTER_ID_COLUMN_NAME = 'poster_id';

    /** @var string[] */
    protected $formattedDates = ['created_at'];

    /** @var string[] */
    protected $dateTimeFormattedDates = ['created_at'];

    /** @var string[] */
    protected $with = [
        'poster'
    ];

    /** @var string[] */
    protected $fillable = [
        self::NOTE_COLUMN_NAME,
        self::USER_ID_COLUMN_NAME,
        self::POSTER_ID_COLUMN_NAME,
    ];

    /**
     * @param $user
     * @return bool
     */
    public function ownedBy($user): bool
    {
        return $this->user_id === $user->id;
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, self::USER_ID_COLUMN_NAME, User::ID_COLUMN_NAME);
    }

    /**
     * @return BelongsTo
     */
    public function poster()
    {
        return $this->belongsTo(User::class, self::POSTER_ID_COLUMN_NAME, User::ID_COLUMN_NAME);
    }
}
