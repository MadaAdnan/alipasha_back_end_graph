<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\CategoryTypeEnum;
use App\Enums\LevelUserEnum;
use App\Observers\UserObserve;
use App\Traits\MediaTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, MediaTrait, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_special' => 'boolean',
        'social' => 'array',
        'notify_date' => 'date',
        'send_at' => 'datetime',

    ];

    protected $appends = [
        'total_views'
    ];
    protected $withCount = [
        'products',
        'following'

    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        self::observe(UserObserve::class);
    }

    public function scopeSeller(Builder $query)
    {
        return $query->whereNotNull('seller_name');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class)->withPivot(['expired_date', 'subscription_date']);
    }

    public function getIsVerifiedEmailAttribute()
    {
        return is_null($this->email_verified_at) ? 'not' : $this->email_verified_at;
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function followers(): HasMany
    {
        return $this->hasMany(UserFollow::class, 'user_id');
    }

    public function following(): HasMany
    {
        return $this->hasMany(UserFollow::class, 'seller_id', 'id');
    }


    public function balances(): HasMany
    {
        return $this->hasMany(Balance::class);
    }

    public function points(): HasMany
    {
        return $this->hasMany(Balance::class);
    }

    public function getFollowMeAttribute(): int
    {
        return \DB::table('user_follow')->where('seller_id', $this->id)->count() ?? 0;
    }

    public function getFollowHimAttribute(): int
    {
        return \DB::table('user_follow')->where('user_id', $this->id)->count() ?? 0;
    }

    public function getTotalBalance(): float
    {
        return \DB::table('balances')->where('user_id', $this->id)->selectRaw('SUM(credit) - SUM(debit) as total')->first()?->total ?? 0;
    }

    public function getTotalPoint(): float
    {
        return \DB::table('points')->where('user_id', $this->id)->selectRaw('SUM(credit) - SUM(debit) as total')->first()?->total ?? 0;
    }

    public function getTotalViewsAttribute(): int
    {
        return ProductView::whereHas('product', fn($query) => $query->where('products.user_id', $this->id))->selectRaw('SUM(count) as count')->first()?->count ?? 0;
    }

    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class)->withPivot(['notify', 'is_manager']);
    }

    public function getTrustAttribute(): bool
    {
        $setting = Setting::first();
        return $setting?->support_id === $this->id || $setting?->delivery_id === $this->id;
    }

}
