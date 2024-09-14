<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Interaction;
use App\Models\User;
use App\Models\UserFollow;

final class FollowAccount
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        /**
         * @var $user User
         *
         */
        $sellerId = $args['id'];
        $user = auth()->user();
        $follow = UserFollow::where(['seller_id' => $sellerId, 'user_id' => $user->id])->exists();
        if ($follow) {
            UserFollow::where(['seller_id' => $sellerId, 'user_id' => $user->id])->delete();


            if (auth()->check()) {
                Interaction::where([
                    'user_id' => auth()->id(),
                    'seller_id' => $sellerId,

                ])->delete();
            }
        } else {
            UserFollow::create(['seller_id' => $sellerId, 'user_id' => $user->id]);


            if(auth()->check() ){
                Interaction::updateOrCreate([
                    'user_id'=>auth()->id(),
                    'seller_id' => $sellerId,

                ],[
                    'visited'=> \DB::raw('visited + 1'),
                ]);
            }
        }
        return $user;


    }
}
