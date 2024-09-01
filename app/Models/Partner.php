<?php

namespace App\Models;

use App\Traits\MediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;

class Partner extends Model implements HasMedia
{
    use HasFactory,MediaTrait;

    protected $guarded = [];
    protected $casts=[
        'expired_date' => 'date'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
