<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WebhokUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly User $user)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       try{
           $response = \Http::post($this->product->user?->url_webhok, [
               'action' => 'update',
               'type'=>'user',
               'data' => [
                   'name' => $this->user->name,
                   'seller_name' => $this->user->seller_name,
                   'phone' =>"{$this->user->phone_code}{$this->user->phone}",
                   'email' => $this->user->email,
                   'address' => $this->user->address,
                   'city' => $this->user?->city?->name,
                   'area' => $this->user?->area?->name,
                   'logo'=>$this->user->getImage('logo'),
                   'primary_color'=>$this->user->id_color,
                   'social'=>$this->user->social
               ]
           ]);

       }catch (\Exception |\Error $e){
           \Log::error($e->getMessage());
       }
    }
}
