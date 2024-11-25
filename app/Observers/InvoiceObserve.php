<?php

namespace App\Observers;

use App\Enums\OrderStatusEnum;
use App\Jobs\SendNotificationJob;
use App\Models\Invoice;
use App\Service\SendNotifyHelper;

class InvoiceObserve
{

    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        try{
            $data['title'] = 'طلب جديد ';
            $data['body'] = "الزبون {$invoice->user->name}  يطلب منتجات من متجرك";
            $data['url'] = 'https://ali-pasha.com/incomming';
            $job=new SendNotificationJob($invoice->seller,$data);
            dispatch($job);
        }catch (\Exception |\Error $e){

        }
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {





    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "restored" event.
     */
    public function restored(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     */
    public function forceDeleted(Invoice $invoice): void
    {
        //
    }
}
