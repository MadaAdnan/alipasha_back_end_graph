<?php

namespace App\Observers;

use App\Enums\CommunityTypeEnum;
use App\Events\MessageSentEvent;
use App\Jobs\SendFirebaseNotificationJob;
use App\Models\Community;
use App\Models\Message;
use App\Models\User;
use Mockery\Exception;


class MessageObServe
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {

        try {
            $community = $message->community;
            $created_at = $community?->messages()->where('messages.id', '<', $message->id)->latest()->first()?->created_at;
            $lastMessage = now()->subMinutes()->equalTo($created_at);


        } catch (\Exception | \Error $e) {
            info($e->getMessage());
        }


        try {
            $ids = [];
            $isSend = false;
            if ($message->community->type == CommunityTypeEnum::CHAT->value) {
                $ids = $community->users()->whereNot('users.id', $message->user_id)->whereNotNull('users.device_token')->pluck('users.device_token')->toArray();
                $name = $community->users()->whereNot('users.id', $message->user_id)->first()?->name ?? '';
                $isSend = true;
            } elseif (($message->community->type == CommunityTypeEnum::GROUP->value || $message->community->type == CommunityTypeEnum::CHANNEL->value)) {
                $name = $community->name;
                $isSend = true;
                /**
                 * @var $user User
                 */
                foreach ($community->users as $user) {
                    if ($user->communities()->find($community->id)?->pivot?->notify) {
                        $ids[] = $user->device_token;
                    }
                }
            }
            if ($isSend) {
                $data = [
                    'title' => 'يوجد رسائل جديدة في المحادثة',
                    'body' => $name,
                    'url' => 'https://ali-pasha.com/communities/' . $message->community->id . '/' . $message->community->type,
                ];
                $job = new SendFirebaseNotificationJob($ids ?? [], $data);
                dispatch($job);
            }
        } catch (\Exception | \Error $e) {
            info('Exception ' . $e->getMessage());
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
