<?php

namespace App\Models;

use App\Observers\BalanceObserve;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Balance extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        self::observe(BalanceObserve::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
