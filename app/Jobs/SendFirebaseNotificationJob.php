<?php

namespace App\Jobs;

use Error;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFirebaseNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ids;
    private $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $ids,array $data)
    {
        //
        $this->ids = $ids;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $firebaseService=new \App\Service\FirebaseService();
        try{
         $firebaseService->sendNotificationToMultipleTokens($this->ids, $this->data);


        }catch (Exception | Error $e){
            info($e->getMessage()) ;
        }
    }
}
