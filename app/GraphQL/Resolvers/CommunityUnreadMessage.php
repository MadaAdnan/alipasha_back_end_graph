<?php

namespace App\GraphQL\Resolvers;

use App\Models\Community;

final class CommunityUnreadMessage
{

    public static function getUnreadMessage($root): int
    {
        /**
         * @var $root Community
         */
        return $root->getUnread(auth()->id());
    }


}
