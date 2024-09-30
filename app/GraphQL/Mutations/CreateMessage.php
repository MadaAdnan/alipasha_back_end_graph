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
if(isset($args['image']) && !empty($args['image'])){
    $file=$args['image'];
    $type=$file->getClientOriginalExtension();
}
        try {
          $message=  Message::create([
                'body' => $args['body'] ?? '',
                'community_id' => $communityId,
                'user_id' => $userId,
                'type'=>$type,
            ]);

       return $message;
        } catch (\Exception | \Error $e) {
            throw new GraphQLExceptionHandler($e->getMessage());
        }
    }
}
