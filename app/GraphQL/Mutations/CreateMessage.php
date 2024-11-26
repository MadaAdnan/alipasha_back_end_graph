<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\CommunityTypeEnum;
use App\Events\MessageSentEvent;
use App\Exceptions\GraphQLExceptionHandler;
use App\Jobs\SendFirebaseNotificationJob;
use App\Models\Community;
use App\Models\Message;
use App\Models\User;
use App\Service\SendNotifyHelper;
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
        try {
            if (isset($args['attach']) && !empty($args['attach'])) {
                $file = $args['attach'];
                $type = $file->getClientOriginalExtension();
            }
        } catch (\Exception $err) {
            \Log::debug('DEBUG:' . $err->getMessage());
        }

        try {
            $message = Message::create([
                'body' => $args['body'] ?? '',
                'community_id' => $communityId,
                'user_id' => $userId,
                'type' => $type,
            ]);

        } catch (\Exception | \Error $e) {
            throw new GraphQLExceptionHandler('Message :' . $e->getLine());
        }

        if ($type !== 'text') {
            try {
                $message->addMedia($args['attach'])->toMediaCollection('attach');
            } catch (\Exception $e) {

                info('UPLOAD : ' . $e->getMessage());
            }
        }
        try {
            $message->community()->update([
                'last_update' => now(),
            ]);
        } catch (\Exception $exception) {
            info('COMM : ' . $e->getMessage());
        }
        try {
            event(new MessageSentEvent($message));
        } catch (Exception $e) {
            info('Error Websockets');
        }
        if($message->community->type==CommunityTypeEnum::CHAT->value && $message->community->messages_count <= 1){

            $user=$message->community->users()->whereNot('users.id',$userId)->first();
            if($user){
                $data=[
                    'title'=>'تواصل جديد عن طريق علي باشا',
                    'body'=>" يريد ".auth()->user()->name." التواصل معك عن طريق الدردشة",
                    'url'=>'https://ali-pasha.com/communities/'.$message->community->id.'/'.$message->community->type,
                ];
                try{
                    SendNotifyHelper::sendNotify($user,$data);
                }catch (\Exception|\Error $e){
\Log::error('CREATE ERROR '.$e->getMessage());
                }

            }

        }
        return $message;
    }
}
