<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Message;

final class CreateMessage
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $userId = auth()->id();
        $communityId = $args['communityId'];
$type='text';
if(isset($args['attach']) && !empty($args['attach'])){
    $file=$args['attach'];
    $type=$file->getClientOriginalExtension();
}
        try {
          $message=  Message::create([
                'body' => $args['body'] ?? '',
                'community_id' => $communityId,
                'user_id' => $userId,
                'type'=>$type,
            ]);
if($type!=='text'){
    $message->addMedia($args['attach'])->toMediaCollection('attach');
}
$message->community()->update([
    'last_update'=>now(),
]);
       return $message;
        } catch (\Exception | \Error $e) {
            throw new GraphQLExceptionHandler($e->getMessage());
        }
    }
}
