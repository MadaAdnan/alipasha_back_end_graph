<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Community;
use App\Models\Message;

final class Messages
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $communityId = $args['community_id'];
        $community = Community::find($communityId);
        if (!$community || ($community->seller_id != auth()->id() && $community->user_id != auth()->id())) {
            throw new \Exception('المحادثة غير موجودة');
        }


        $messages = Message::where('community_id', $communityId)->latest();
       // $messages->where('user_id', '!=', auth()->id())->update(['is_seen'=> 1]);
        return $messages;
    }
}
