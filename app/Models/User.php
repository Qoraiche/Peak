<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Qoraiche\Peak\Models\User as PeakUser;
use function Illuminate\Events\queueable;

class User extends PeakUser
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'referrer_id',
        'referrer_code'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret'
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'role_names'
    ];

    /**
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            do {
                $code = Str::random(10);
            } while (User::where('referral_code', $code)->exists());

            $user->referral_code = $code;
        });

        // assign default user registration
        static::created(function ($user) {
            $defaultRoles = config('peak.default_roles.user', []);

            if (!blank($defaultRoles)) {
                // Directly pass to assignRole, whether it's a string or an array
                $user->assignRole($defaultRoles);
            }
        });

        static::updated(queueable(function ($user) {
            if ($user->hasStripeId()) {
                $user->syncStripeCustomerDetails();
            }
        }));
    }

    /**
     * @return string
     */
    public function preferredLocale(): string
    {
        return $this->preferred_locale ?? 'en-US'; // en-US for stripe payments
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function licenses()
    {
        return $this->hasMany(License::class);
    }
}
