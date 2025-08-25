<?php

namespace App\Http\Resources\WebHok;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'seller_name' => $this->seller_name,
            'phone' => "{$this->phone_code}{$this->phone}",
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this?->city?->name,
            'area' => $this?->area?->name,
            'logo' => $this->getImage('image'),
            'primary_color' => $this->id_color,
            'social' => $this->social
        ];
    }
}
