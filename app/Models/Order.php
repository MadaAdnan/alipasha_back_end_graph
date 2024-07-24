<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function from(): BelongsTo
    {
        return $this->belongsTo(City::class, 'from_id');
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(City::class, 'to_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
