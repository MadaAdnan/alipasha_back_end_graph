<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Community extends Model
{
    use HasFactory;

    protected $guarded = [];
protected $withCount=[
    'users',
];
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function scopeChannel(Builder $query): Builder
    {
        return $query->where('type','channel');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->take(3);
    }

    public function allUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }




}
