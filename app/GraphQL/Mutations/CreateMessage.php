<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Events\MessageSentEvent;
use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Message;
use Mockery\Exception;

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

        $type = 'text';
        try{
            if (isset($args['attach']) && !empty($args['attach'])) {
                $file = $args['attach'];
                $type = $file->getClientOriginalExtension();
            }
        }catch (\Exception $err){
            \Log::debug('DEBUG:'.$err->getMessage());
        }

        try {
            $message = Message::create([
                'body' => $args['body'] ?? '',
                'community_id' => $communityId,
                'user_id' => $userId,
                'type' => $type,
            ]);
        } catch (\Exception | \Error $e) {
            throw new GraphQLExceptionHandler('Message :'.$e->getLine());
        }

            if ($type !== 'text') {
                try{
                    $message->addMedia($args['attach'])->toMediaCollection('attach');
                    info('UPLOAD : success');
                }catch (\Exception $e){

                    info('UPLOAD : '.$e->getMessage());
                }
            }
            try{
                $message->community()->update([
                    'last_update' => now(),
                ]);
            }catch (\Exception $exception){
                info('COMM : '.$e->getMessage());
            }
        try{
               event(new MessageSentEvent($message));
        }catch (Exception $e){
            info('Error Websockets');
        }

        return $message;
    }
}
