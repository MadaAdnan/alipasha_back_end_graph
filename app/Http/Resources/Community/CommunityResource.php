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
            'user' => new UserResource($this->user),
            'seller' => new UserResource($this->seller),
            'last_update' => $this->last_update->diffForHumans(),
           // 'not_seen_count' => $this->getUnread(auth()->id() == $this->user_id ? $this->user_id : $this->seller_id),

        ];
    }

}
