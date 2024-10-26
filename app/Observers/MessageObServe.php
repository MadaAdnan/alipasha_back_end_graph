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
        info('test');
       try{
           $community = $message->community;
           $created_at = $community?->messages()->where('id','<',$message->id)->latest()->first()?->created_at;
           $lastMessage = now()->subMinutes()->greaterThanOrEqualTo($created_at);
           info('++++++++++++');
           info($created_at);
           info($lastMessage);
           info('++++++++++++');

           if ($lastMessage) {
               info('LATEST');
               $ids = $community->users()->whereNot('id', $message->user_id)->whereNotNull('device_token')->pluck('device_token')->toArray();
               $data = ['title' => 'يوجد رسائل جديدة في المحادثة'];
               if ($community->type == CommunityTypeEnum::CHAT->value) {
                   $name = $community->users()->whereNot('users.id', $message->user_id)->first()?->name??'';
               } else {
                   $name = $community->name;
               }
               $data['body'] = $name;
               try {
                   info('job');
                SendFirebaseNotificationJob::dispatch($ids??[], $data);
                   //dispatch($job);
               } catch (\Exception | \Error $e) {
                   info('Exception '.$e->getMessage());
               }
           }
       }catch (\Exception |\Error $e){}

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
