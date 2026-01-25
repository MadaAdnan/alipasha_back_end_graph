<?php

namespace App\Http\Resources\Community;

use App\GraphQL\Resolvers\CommunityUnreadMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunityResource extends JsonResource
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
            'name'=>$this->name,
           'users'=>UserResource::collection($this->users()->limit(3)->get()),
            'last_update' => $this->last_update->diffForHumans(),
            'type'=>$this->type,
            'url'=>$this->url,
            'users_count'=>$this->users->count(),
            'image'=>$this->getImage(),
            'created_at'=>$this->created_at->diffForHumans(),
        ];
    }

}
