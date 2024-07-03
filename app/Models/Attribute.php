<?php

namespace App\Models;

use App\Enums\AttributeTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory;


    protected $guarded = [];


    public function scopeLimited($query)
    {
        return $query->where('type', AttributeTypeEnum::LIMIT->value);
    }

    public function scopeMultiple($query)
    {
        return $query->where('type', AttributeTypeEnum::MULTIPLE->value);
    }

    public function scopeWrite($query)
    {
        return $query->where('type', AttributeTypeEnum::VALUE->value);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(__CLASS__);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(__CLASS__);
    }
}
