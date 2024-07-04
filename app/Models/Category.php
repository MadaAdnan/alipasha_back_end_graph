<?php

namespace App\Models;

use App\Enums\CategoryTypeEnum;
use App\Observers\CategoryObserve;
use App\Traits\MediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;

class Category extends Model implements HasMedia
{
    use HasFactory, MediaTrait,SoftDeletes;

    protected $guarded = [];
    protected $withCount=[
        'products',
        'products2'
    ];

    protected static function boot()
    {
        parent::boot();
        self::observe(CategoryObserve::class);
    }

    public function scopeProduct($query)
    {
        return $query->where('categories.type', CategoryTypeEnum::PRODUCT->value);
    }

    public function scopeJob($query)
    {
        return $query->where('categories.type', CategoryTypeEnum::JOB->value)->orWhere('categories.type', CategoryTypeEnum::SEARCH_JOB->value);
    }

    public function scopeTender($query)
    {
        return $query->where('categories.type', CategoryTypeEnum::TENDER->value);
    }

    public function scopeNews($query)
    {
        return $query->where('categories.type', CategoryTypeEnum::NEWS->value);
    }

    public function scopeService($query)
    {
        return $query->where('categories.type', CategoryTypeEnum::SERVICE->value);
    }


    public function parents()
    {
        return $this->belongsToMany(__CLASS__, 'category_parent', 'category_id', 'parent_id');
    }

    public function children()
    {
        return $this->belongsToMany(__CLASS__, 'category_parent', 'parent_id', 'category_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    public function products2(): HasMany
    {
        return $this->hasMany(Product::class, 'sub1_id');
    }


}
