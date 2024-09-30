<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Community;
use App\Models\Message;

final class GetMessages
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $communityId = $args['communityId'];
        $userId = auth()->id();
        $community = Community::whereHas('users', fn($query) => $query->where('users.id', $userId))->where('id', $communityId)->first();
        if ($community != null) {
            return Message::where('community_id', $communityId)->latest();
        }
        throw new GraphQLExceptionHandler("المجتمع غير موجود");

    }
}
