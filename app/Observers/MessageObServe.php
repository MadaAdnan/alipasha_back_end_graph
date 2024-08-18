<?php

namespace App\Observers;

use App\Events\MessageSentEvent;
use App\Models\Message;

class MessageObServe
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {

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
