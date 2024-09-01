<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SliderView extends Model
{
    use HasFactory;

    protected $table = 'slider_views';
    protected $guarded = [];

    public function slider(): BelongsTo
    {
        return $this->belongsTo(Slider::class);
    }
}
