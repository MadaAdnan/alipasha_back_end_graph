<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Letter extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function message(): MorphTo
    {
      return  $this->morphTo('message');
    }
}
