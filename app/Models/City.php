<?php

namespace App\Models;

use App\Traits\MediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

class City extends Model implements HasMedia
{
    use HasFactory, MediaTrait;

    protected $guarded = [];

    public function city(): BelongsTo
    {
        return $this->belongsTo(__CLASS__);
    }

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__);
    }
}
