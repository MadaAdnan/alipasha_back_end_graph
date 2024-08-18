<?php

namespace App\Http\Resources\Community;

use App\GraphQL\Resolvers\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attachUrl = Image::getAttach($this);
        info("RESOURCE :".$attachUrl);
        return [
            "id" => $this->id,
            'message' => $this->message,
            'user' => new UserResource($this->user),
            'community' => new CommunityResource($this->community),
            'created_at' => $this->created_at->diffForHumans(),
            'attach'=>$attachUrl,
        ];
    }
}
