<?php

namespace App\Models;

use App\Enums\ProductActiveEnum;
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
    protected static function booted()
    {
        self::addGlobalScope('sort',fn($query)=>$query->orderBy('sortable'));
        parent::booted(); // TODO: Change the autogenerated stub
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(__CLASS__);
    }

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class)->where('products.active',ProductActiveEnum::ACTIVE->value);
    }
}
