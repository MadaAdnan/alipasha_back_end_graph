<?php

namespace App\Http\Resources\Setting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'address' => $this->address,
           // 'longitude' => $this->longitude,
           // 'latitude' => $this->latitude,
            'weather_api' => $this->weather_api,
            'current_version' => $this->current_version,
            'force_upgrade' =>(bool) $this->force_upgrade,
            'advice_url' => $this->advice_url,
            'active_advice' => (bool)$this->active_advice,
            'delivery_service' => $this->delivery_service,
            'auto_update_exchange' =>(bool) $this->auto_update_exchange,
           // 'about' => $this->about,
           // 'privacy' => $this->privacy,
            'active_live' => (bool)$this->active_live,
            'live_id' => $this->live_id,
           // 'social' => $this->social,
            'less_amount_point_pull' => $this->less_amount_point_pull,
        ];
    }
}
