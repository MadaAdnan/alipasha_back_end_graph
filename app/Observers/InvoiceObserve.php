<?php

namespace App\Observers;

use App\Enums\OrderStatusEnum;
use App\Jobs\SendNotificationJob;
use App\Models\Invoice;

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
        $newStatus=$invoice->status;
        $oldStatus= $invoice->getOriginal('status');
       if($invoice->isDirty('status') && $newStatus !== $oldStatus ){

           switch ($newStatus){
               case OrderStatusEnum::CANCELED->value:
                   $data['title'] = "طلب رقم {$invoice->id}";
                   $data['body'] = "للأسف البضاعة غير متوفرة حالياً";
                   $data['url'] = 'https://ali-pasha.com/exports';
                   break;
               case OrderStatusEnum::AGREE->value:
                   $data['title'] = "طلب رقم {$invoice->id}";
                   $data['body'] = "تهانينا تم قبول الطلب من التاجر يتم الآن متابعة الطلب للشحن";
                   $data['url'] = 'https://ali-pasha.com/exports';
                   break;
               case OrderStatusEnum::AWAY->value:
                   $data['title'] = "طلب رقم {$invoice->id}";
                   $data['body'] = "جاري الشحن , الطلب بالطريق إليكم";
                   $data['url'] = 'https://ali-pasha.com/exports';
                   break;
               case OrderStatusEnum::COMPLETE->value:
                   $data['title'] = "طلب رقم {$invoice->id}";
                   $data['body'] = "تم تسليمكم الطلب شكراً لثقتكم";
                   $data['url'] = 'https://ali-pasha.com/exports';
                   break;

           }
           try {
//               $job=new SendNotificationJob($invoice->user,$data);
//               dispatch($job);
           }catch (\Exception | \Error $e){}
           if($newStatus==OrderStatusEnum::COMPLETE->value){
               try {
                   $data['title'] = "طلب رقم {$invoice->id}";
                   $data['body'] = "تهانينا أتممت عملية بيع ناجحة";
                   $data['url'] = 'https://ali-pasha.com/exports';

//                   $job=new SendNotificationJob($invoice->seller,$data);
//                   dispatch($job);
               }catch (\Exception | \Error $e){}
           }

       }
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
