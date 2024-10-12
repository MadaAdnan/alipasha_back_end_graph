<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $emailable;

    /**
     * Create a new job instance.
     */
    public function __construct($user,$emailable)
    {
        //
        $this->user = $user;
        $this->emailable = $emailable;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->users)->send($this->emailable);
    }
}
