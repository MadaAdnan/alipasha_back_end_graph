<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class RefreshExchangeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usd:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $setting = Setting::first();
            if ($setting->auto_update_exchange) {
                $response = \Http::get('https://northsoftit.com/ER');
                $data = ['dollar_value' => $setting->dollar_value, 'dollar_syr' => $setting->dollar_syr,'dollar'=>$setting->dollar];
                if ($response->successful() && isset($response->json()[0]['saleRate'])) {
                    $usd = $response->json()[0];
                    $data['dollar_value'] = $usd['saleRate'];
                    $data['dollar']['idlib']['usd']=['sale'=>$usd['buyRate'],'bay'=>$usd['saleRate']];
                    $data['dollar']['izaz']['usd']=['sale'=>$usd['buyRate'],'bay'=>$usd['saleRate']];
                    if (isset($response->json()[1]['saleRate'])) {
                        $syr = $response->json()[1];
                        $data['dollar_syr'] = $syr['saleRate'] * 10000;
                        $data['dollar']['idlib']['syr']=['sale'=>$syr['buyRate']*10000,'bay'=>$syr['saleRate'] * 10000];
                        $data['dollar']['izaz']['syr']=['sale'=>$syr['buyRate']*10000,'bay'=>$syr['saleRate'] * 10000];
                    }
                    $setting->update($data);

                }
            }

        } catch (\Exception $e) {
        }
    }
}
