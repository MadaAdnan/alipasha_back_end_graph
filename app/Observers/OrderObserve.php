<?php

namespace App\Observers;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Service\SendNotifyHelper;

class OrderObserve
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        if ($order->status !== $order->getOriginal('status') && $order->status == OrderStatusEnum::AGREE->value) {
            $user = $order->user;
            $data['title'] = 'قبول الطلب';
            $data['body'] = 'تم قبول الطلب سيصل إليك فريق الشحن قريباً لإستلام البضاعة  ';
            $data['url'] = 'https://ali-pasha.com/orders/' . $order->id;

            SendNotifyHelper::sendNotify($user, $data);
        }
//
        elseif ($order->status !== $order->getOriginal('status') && $order->status == OrderStatusEnum::AWAY->value) {
            $user = $order->user;
            $data['title'] = ' الطلب في الطريق';
            $data['body'] = 'تم تحميل البضاعة , في الطريق إلى الوجهة';
            $data['url'] = 'https://ali-pasha.com/orders/' . $order->id;

            SendNotifyHelper::sendNotify($user, $data);

        }
        //
        elseif ($order->status !== $order->getOriginal('status') && $order->status == OrderStatusEnum::COMPLETE->value) {
            $user = $order->user;
            $data['title'] = 'تم تسليم الطلب ';
            $data['body'] = 'تم تسليم الطلب بنجاح نشكركم على ثقتكم ';
            $data['url'] = 'https://ali-pasha.com/orders/' . $order->id;

            SendNotifyHelper::sendNotify($user, $data);

        }
        //
        elseif ($order->status !== $order->getOriginal('status') && $order->status == OrderStatusEnum::CANCELED->value) {
            $user = $order->user;
            $data['title'] = 'تم رفض الطلب';
            $data['body'] = 'للأسف تم رفض طلب الشحن يرجى التواصل مع الإدارة';
            $data['url'] = 'https://ali-pasha.com/orders/' . $order->id;

            SendNotifyHelper::sendNotify($user, $data);

        }

    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
