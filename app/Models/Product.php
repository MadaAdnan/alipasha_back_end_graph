<?php

namespace App\Models;

use App\Enums\CategoryTypeEnum;
use App\Enums\ProductActiveEnum;
use App\Traits\MediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, MediaTrait, Searchable, SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'tags' => 'array'
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => (string)$this->id,
            'name' => $this->name,
            'info' => $this->info,
            'tags' => $this->tags,
        ];
    }

    public function searchableAs()
    {
        return 'search_post';
    }

    public function scopePending($query)
    {
        return $query->where('active', ProductActiveEnum::PENDING->value);
    }

    public function scopeNotPending($query)
    {
        return $query->where('active', '!=', ProductActiveEnum::PENDING->value);
    }

    public function scopeProduct($query)
    {
        return $query->where('type',CategoryTypeEnum::PRODUCT->value);
    }

    public function scopeJob($query)
    {
        return $query->where('type',CategoryTypeEnum::JOB->value)->orWhere('type',CategoryTypeEnum::SEARCH_JOB->value);
    }

    public function scopeTender($query)
    {
        return $query->where('type',CategoryTypeEnum::TENDER->value);
    }

    public function scopeNews($query)
    {
        return $query->where('type',CategoryTypeEnum::NEWS->value);
    }

    public function scopeService($query)
    {
        return $query->where('type',CategoryTypeEnum::SERVICE->value);
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
        return $this->belongsTo(City::class)->withDefault(fn()=>$this->user->city);
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

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class);
    }

    public function getViewsCountAttribute(): int
    {
        return $this->views->sum('count');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class, 'product_id');
    }
}
