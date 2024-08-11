<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Community;
use App\Models\Message;

final class CreateMessage
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
      //$user_id=auth()->id();
      $user_id=$args['userId'];
      $seller_id=$args['sellerId'];
      $community=Community::where(['user_id'=>$user_id,'seller_id' => $seller_id])
          ->orWhere(['user_id'=>$seller_id,'seller_id' => $user_id])->first();
      if(!$community){
          $community=Community::create([
              'user_id'=>$user_id,
              'seller_id'=>$seller_id,
          ]);
      }

        $message=Message::create([
            'user_id'=>$user_id,
            'message'=>$args['message']??'',
            'community_id'=>$community->id,
        ]);
        if (isset($args['attach'])) {

            $message->addMedia($args['attach'])->toMediaCollection('attach');

        }
        return $message;
    }
}
