<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdviceView extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function advice(): BelongsTo
    {
        return $this->belongsTo(Advice::class);
    }
}
