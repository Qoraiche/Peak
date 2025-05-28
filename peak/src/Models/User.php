<?php

namespace Qoraiche\Peak\Models;

use Database\Factories\UserFactory;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Qoraiche\Peak\Settings\Admin\WebsiteSecuritySettings;
use Qoraiche\Peak\Traits\HasExportableData;
use Qoraiche\Peak\Traits\HasFormattedDates;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Tags\HasTags;
use Illuminate\Support\Str;
use function Illuminate\Events\queueable;

class User extends Authenticatable implements HasMedia, HasLocalePreference, MustVerifyEmail
{
    use HasRoles;
    use HasApiTokens;
    use HasFactory;
    use HasFormattedDates;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasTags;
    use HasTeams;
    use InteractsWithMedia;
    use HasExportableData;

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(config('peak.default_roles.admin'));
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->hasRole(config('peak.default_roles.user'));
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    const NAME_COLUMN_NAME = 'name';
    const EMAIL_COLUMN_NAME = 'email';
    const ID_COLUMN_NAME = 'id';
    const MOBILE_NUMBER_COLUMN_NAME = 'mobile_number';
    const ADDRESS_COLUMN_NAME = 'address';
    const COUNTRY_COLUMN_NAME = 'country';
    const CITY_COLUMN_NAME = 'city';
    const STATE_COLUMN_NAME = 'state';
    const POSTAL_CODE_COLUMN_NAME = 'postal_code';
    const PASSWORD_COLUMN_NAME = 'password';
    const GENDER_COLUMN_NAME = 'gender';
    const TIMEZONE_COLUMN_NAME = 'timezone';
    const RECEIPT_EMAILS_COLUMN_NAME = 'receipt_emails';
    const PREFERRED_LOCALE_COLUMN_NAME = 'preferred_locale';
    const ADDRESS_LINE_1_COLUMN_NAME = 'address_line_1';
    const ADDRESS_LINE_2_COLUMN_NAME = 'address_line_2';
    const CURRENCY_COLUMN_NAME = 'currency';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'email',
        'mobile_number',
        'address',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'gender',
        'referrer_id',
        'referral_code',
        'timezone',
        'postal_code',
        'country',
        'currency',
        'preferred_locale',
        'payment_method',
        'tax_percentage',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'current_team_id',
        'profile_photo_path',
        'bio',
        'twitter',
        'facebook',
        'instagram',
        'youtube',
        'discord',
        'tiktok',
        'extra_billing_information',
        'billing_address',
        'billing_address_line_2',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'vat_id',
        'receipt_emails',
        'billing_country',
        'pm_type',
        'pm_last_four',
        'pm_expiration'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'receipt_emails' => 'array'
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
        'two_factor_secret',
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'verified_email',
        'has_enabled_two_factor_authentication',
        'profile_photo_url',
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

        static::updated(queueable(function (User $user) {
            if ($user->hasStripeId()) {
                $user->syncStripeCustomerDetails();
            }
        }));
    }

    /**
     * @return Collection
     */
    public function getRoleNamesAttribute()
    {
        return $this->getRoleNames();
    }

    /**
     * @return Collection
     */
    public function getPermissionNamesAttribute()
    {
        return $this->getPermissionNames();
    }

    /**
     * Define exportable columns with optional transformations.
     *
     * @return array
     */
    public function getExportableColumns(): array
    {
        return [
            'id' => fn($user) => $user->id,
            'name' => fn($user) => $user->name,
            'email' => fn($user) => $user->email
        ];
    }

    /**
     * @return bool
     */
    public function canBeImpersonated()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canImpersonate()
    {
        return $this->hasRole(config('peak.default_roles.admin'));
    }

    /**
     * @param $type
     * @param $price
     * @return bool
     */
    public function stripeSubscribed($type = 'default', $price = null)
    {
        /** @var Subscription $subscription */
        $subscription = $this->subscription($type);

        if (!$subscription || !$subscription->valid()) {
            return false;
        }

        return !$price || $subscription->hasStripePrice($price);
    }

    /**
     * @param $type
     * @param $price
     * @return bool
     */
    public function paddleSubscribed($type = 'default', $price = null)
    {
        /** @var Subscription $subscription */
        $subscription = $this->subscription($type);

        if (!$subscription || !$subscription->valid()) {
            return false;
        }

        return $price ? $subscription->hasPaddlePrice($price) : true;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeVerified(Builder $query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * @return HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * @return HasMany
     */
    public function userNotes(): HasMany
    {
        return $this->hasMany(UserNote::class);
    }

    /**
     * @param Roadmap $roadmap
     * @return bool
     */
    public function hasUpvotedRoadmap(Roadmap $roadmap): bool
    {
        return $this->upvotedRoadmaps()->where('roadmap_id', $roadmap->id)->exists();
    }

    /**
     * @return BelongsToMany
     */
    public function upvotedRoadmaps()
    {
        return $this->belongsToMany(Roadmap::class, 'roadmap_user_upvotes')->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @return HasMany
     */
    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    /**
     * @return BelongsTo
     */
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /**
     * @return bool
     */
    public function getVerifiedEmailAttribute(): bool
    {
        return $this->hasVerifiedEmail();
    }

    /**
     * @return bool
     * @todo: hasVerifiedEmail
     *
     */
    public function hasVerifiedEmail()
    {
        return $this->email_verified_at !== null;
    }

    /**
     * @return bool
     */
    public function getHasEnabledTwoFactorAuthenticationAttribute(): bool
    {
        return $this->hasEnabledTwoFactorAuthentication();
    }

    public function hasEnabledTwoFactorAuthentication()
    {
        $securitySettings = app(WebsiteSecuritySettings::class);

        if (!$securitySettings->two_factor_auth_enabled) {
            return false;
        }

        if (Fortify::confirmsTwoFactorAuthentication()) {
            return !is_null($this->two_factor_secret) &&
                !is_null($this->two_factor_confirmed_at);
        }

        return !is_null($this->two_factor_secret);
    }

    /**
     * @return array
     */
    public function stripePreferredLocales()
    {
        return [$this->preferredLocale()];
    }

    /**
     * @return string
     */
    public function preferredLocale(): string
    {
        return $this->preferred_locale ?? 'en-US'; // en-US for stripe payments
    }

    /**
     * @return mixed
     */
    public function stripePhone()
    {
        return $this->getAttribute(self::MOBILE_NUMBER_COLUMN_NAME);
    }

    /**
     * @return string[]
     */
    public function stripeAddress()
    {
        return [
            'city' => $this->city,
            'country' => $this->country,
            'line1' => $this->address_line_1,
            'line2' => $this->address_line_2,
            'postal_code' => $this->postal_code,
            'state' => $this->state,
        ];
    }

    /**
     * @return string
     */
    public function stripeName(): string
    {
        return $this->name;
    }

    /**
     * @return HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * @return mixed
     */
    public function currentSubscriptionPricing()
    {
        return SubscriptionPlanPricing::where('stripe_id', $this->subscription()?->stripe_price)->first();
    }

    /**
     * @return string|null
     */
    public function currentSubscriptionPlanId(): ?string
    {
        if (!$this->subscribed()) {
            return null;
        }

        if ($this->subscription()->provider === Subscription::STRIPE_PROVIDER_NAME) {
            return $this->subscription()->stripe_price;
        }

        if ($this->subscription()->provider === Subscription::PADDLE_PROVIDER_NAME) {
            return $this->subscription()->paddle_price;
        }

        return null;
    }

    /** ----------------------------- SaaS Subscription -------------------------- */

    /**
     * This method used to attach a free plan (if exists) to a newly created user.
     *
     * @return BelongsTo
     */
    public function freeSubscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /**
     * @return mixed|void
     */
    public function activeSubscription()
    {
        if ($this->subscriptionPlan) {
            return $this->subscriptionPlan;
        }

        if ($this->subscribed()) {
            return app(BillableService::class)->getUserCurrentSubscriptionPriceInfo($this);
        }
    }

    /**
     * @return MorphMany
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * @return Repository|Application|mixed|object|null
     */
    protected function profilePhotoDisk()
    {
        return config('media-library.disk_name');
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
            'billing_updated_at' => 'datetime',
            'password' => 'hashed',
            'trial_ends_at' => 'datetime'
        ];
    }

    /**
     * @param $paymentMethod
     * @return $this
     */
    protected function fillPaymentMethodDetails($paymentMethod)
    {
        if ($paymentMethod->type === 'card') {
            $this->pm_type = $paymentMethod->card->brand;
            $this->pm_last_four = $paymentMethod->card->last4;
            $this->pm_expiration = $paymentMethod->card->exp_month . '/' . $paymentMethod->card->exp_year;
        } else {
            $this->pm_type = $type = $paymentMethod->type;
            $this->pm_last_four = optional($paymentMethod)->$type->last4;
            $this->pm_expiration = null;
        }

        return $this;
    }
}
