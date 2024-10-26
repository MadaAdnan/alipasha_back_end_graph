<?php

namespace App\Observers;

use App\Enums\CommunityTypeEnum;
use App\Events\MessageSentEvent;
use App\Jobs\SendFirebaseNotificationJob;
use App\Models\Community;
use App\Models\Message;
use Mockery\Exception;


class MessageObServe
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {

       try{
           $community = $message->community;
           $created_at = $community?->messages()->where('messages.id','<',$message->id)->latest()->first()?->created_at;
           $lastMessage = now()->subMinutes()->greaterThanOrEqualTo($created_at);


           if ($lastMessage) {
            try{

                $ids = $community->users()->whereNot('users.id', $message->user_id)->whereNotNull('users.device_token')->pluck('users.device_token')->toArray();
                $data = ['title' => 'يوجد رسائل جديدة في المحادثة'];
                if ($community->type == CommunityTypeEnum::CHAT->value) {
                    $name = $community->users()->whereNot('users.id', $message->user_id)->first()?->name??'';
                } else {
                    $name = $community->name;
                }

                $data['body'] = $name;
                try {
                    info('test not'.$name);
                    SendFirebaseNotificationJob::dispatch($ids??[], $data);
                    //dispatch($job);
                } catch (\Exception | \Error $e) {
                    info('Exception '.$e->getMessage());
                }
            }catch (\Exception | \Error $e){
                throw new \Exception($e->getLine());
            }
           }
       }catch (\Exception |\Error $e){
           info($e->getMessage());
       }

    }

    /**
     * Handle the Message "updated" event.
     */
    public function updated(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "deleted" event.
     */
    public function deleted(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "restored" event.
     */
    public function restored(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "force deleted" event.
     */
    public function forceDeleted(Message $message): void
    {
        //
    }
}
