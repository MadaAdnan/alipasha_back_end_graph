<?php

namespace App\Models;

use AnourValar\EloquentSerialize\Tests\Models\User;
use App\Traits\MediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;

class Room extends Model implements HasMedia
{
    use HasFactory,MediaTrait;

    public function letters(){
        return $this->morphToMany(Letter::class,'message');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot(['notify']);
    }
}
