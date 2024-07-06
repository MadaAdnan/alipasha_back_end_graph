<?php

namespace App\Models;

use App\Traits\MediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;

class Setting extends Model implements HasMedia
{
    use HasFactory, MediaTrait;

    protected $guarded = [];
    protected $casts = [
        'social' => 'array',
        'url_for_download' => 'array',
        'dollar_value' => 'float',
        'point_value' => 'float',
        'num_point_for_register' => 'float',
        'less_amount_point_pull' => 'float',
        'longitude' => 'float',
        'latitude' => 'float',
        'available_country' => 'bool',
        'available_any_email' => 'bool',
        'auto_update_exchange' => 'bool',
        'active_points' => 'bool',
        'delivery_service' => 'bool',
        'active_live' => 'bool',
        'send_notification_hobbies' => 'bool',
        'force_upgrade' => 'bool',
        'active_advice' => 'bool',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
