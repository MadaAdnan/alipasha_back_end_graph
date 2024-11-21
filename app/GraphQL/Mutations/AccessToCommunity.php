<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\CommunityTypeEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Community;

final  class AccessToCommunity
{
    /** @param array{} $args */
    public function __invoke($_, array $args)
    {
        $community = Community::whereIn('type', [
            CommunityTypeEnum::CHANNEL->value,
            CommunityTypeEnum::GROUP->value,
        ])->where('url', $args['code'])->first();
        if ($community == null) {
            throw new GraphQLExceptionHandler('لم يتم العثور على المجتمع يرجى التأكد من كود الدخول');
        }
        if ($community->users()->where('user_id', auth()->id())->exists()) {
            throw new GraphQLExceptionHandler('انت موجود في المجتمع بالفعل');
        }
        $community->users()->attach(auth()->id());

        return $community;
    }
}
