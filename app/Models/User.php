<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\CategoryTypeEnum;
use App\Enums\LevelUserEnum;
use App\Traits\MediaTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, MediaTrait;

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
        'is_special' => 'boolean'
    ];


    protected $withCount = [
        'products',

    ];

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

}
