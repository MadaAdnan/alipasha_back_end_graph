<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Events\MessageSentEvent;
use App\Models\Community;
use App\Models\Message;

final class CreateMessage
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {

        //$user_id=auth()->id();
        $user_id = $args['userId'];
        $seller_id = $args['sellerId'];
        $community = Community::where(fn($query) => $query->where(['user_id' => $user_id, 'seller_id' => $seller_id]))
            ->orWhere(fn($query) => $query->where(['user_id' => $seller_id, 'seller_id' => $user_id]))->first();
        if (!$community) {
            $community = Community::create([
                'user_id' => $user_id,
                'seller_id' => $seller_id,
                'last_change' => now(),
            ]);
        } else {
            $community->update(['last_change' => now()]);
        }

        $message = Message::create([
            'user_id' => $user_id,
            'message' => $args['message'] ?? '',
            'community_id' => $community->id,
        ]);
        if (isset($args['attach'])) {

            $message->addMedia($args['attach'])->toMediaCollection('attach');

        }
        try {
            event(new MessageSentEvent($message));
        } catch (\Exception | \Error $e) {
        }
        return $message;
    }
}
