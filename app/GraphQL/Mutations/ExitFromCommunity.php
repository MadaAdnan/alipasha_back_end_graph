<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Community;

final  class ExitFromCommunity
{
    /** @param array{} $args */
    public function __invoke($_, array $args)
    {
        $community = Community::find($args['communityId']);
        if (!$community->is_global) {
            $community->users()->detach(auth()->id());
        } elseif ($community->is_global==true || auth()->id() == $community->manager_id) {
            throw new GraphQLExceptionHandler('لا يمكنك الخروج من هذا المجتمع');
        }
        return $community;
    }
}
