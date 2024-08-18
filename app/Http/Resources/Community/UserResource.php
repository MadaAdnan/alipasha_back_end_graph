<?php

namespace App\Http\Resources\Community;

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
            'id' => $this->id,
            'name' => $this->name,
            'seller_name' => $this->seller_name,
            'image' => $this->hasMedia('image') ? $this->getFirstMediaUrl('image', 'webp') : url('/') . asset('images/noImage.jpeg'),
            'logo' => $this->hasMedia('logo') ? $this->getFirstMediaUrl('logo', 'webp') : url('/') . asset('images/noImage.jpeg'),
        ];
    }
}
