<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Models\User;
use App\Models\UserFollow;

final class UnFollowing
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $user = User::find($args['id']);
        if (!$user) {
            throw new GraphQLExceptionHandler('المستخدم غير موجود');
        }
        UserFollow::where('seller_id', auth()->id())->where('user_id', $user->id)->delete();
        return $user;
    }
}
