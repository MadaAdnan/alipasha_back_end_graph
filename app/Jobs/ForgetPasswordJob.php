<?php

namespace App\Jobs;

use App\Mail\ForgetPasswordEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ForgetPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $users;
    private $mailable;

    /**
     * Create a new job instance.
     */
    public function __construct($users,$mailable)
    {
        //
        $this->users = $users;
        $this->mailable = $mailable;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Mail::to($this->users)->send($this->mailable);
    }
}
