<?php

namespace App\Http\Resources\WebHok;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'price' => $this->getPrice(),
            'price_syr'=>$this->getSyrPrice(),
            'price_tr'=>$this->getTurkeyPrice(),
            'image' => $this->getImageForceSiteMap(),
            'images' => $this->getImages(),
            'expert' => $this->expert,
            'info' => $this->info,
            'url' => $this->url,
            'email' => $this->email,
            'phone' => "{$this->user?->phone_code}" . "{$this->user?->phone}",
            'address' => $this->address,
            'city' => $this->user?->city?->name,
            'area' => $this->user?->area?->name,
            'is_available' =>(bool) $this->is_available,
            'video'=>$this->video,
            'category_name'=>$this->sub1?->name,
            'created_at'=>$this->created_at,
            'active'=>$this->active,
        ];
    }
}
