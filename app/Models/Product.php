<?php

namespace App\Models;

use App\Enums\CategoryTypeEnum;
use App\Traits\MediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, MediaTrait;

    protected $guarded = [];
    protected $casts = [
        'tags' => 'array'
    ];

    public function scopeProduct($query)
    {
        return $query->whereHas('category', fn($query) => $query->where('categories.type', CategoryTypeEnum::PRODUCT->value));
    }

    public function scopeJob($query)
    {
        return $query->whereHas('category', fn($query) => $query->where('categories.type', CategoryTypeEnum::JOB->value)->orWhere('categories.type', CategoryTypeEnum::SEARCH_JOB->value));
    }

    public function scopeTender($query)
    {
        return $query->whereHas('category', fn($query) => $query->where('categories.type', CategoryTypeEnum::TENDER->value));
    }

    public function scopeNews($query)
    {
        return $query->whereHas('category', fn($query) => $query->where('categories.type', CategoryTypeEnum::NEWS->value));
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sub1(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub1_id', 'id');
    }

    public function sub2(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub2_id');
    }

    public function sub3(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub3_id');
    }

    public function sub4(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub4_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function attributeProduct(): HasMany
    {
        return $this->hasMany(AttributeProduct::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'attribute_products', 'product_id');
    }

    public function views(): HasMany
    {
        return $this->hasMany(ProductView::class, 'product_id');
    }

    public function getViewsCountAttribute(): int
    {
        return $this->views->sum('count');
    }
}
