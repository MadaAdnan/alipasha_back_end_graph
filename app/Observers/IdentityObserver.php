<?php

namespace App\Observers;

use App\Enums\OrderStatusEnum;
use App\Models\Identity;
use App\Service\SendNotifyHelper;

class IdentityObserver
{
    /**
     * Handle the Identity "created" event.
     */
    public function created(Identity $identity): void
    {
        //
    }

    /**
     * Handle the Identity "updated" event.
     */
    public function updated(Identity $identity): void
    {
        $oldStatus = $identity->getOriginal('status');
        $newStatus = $identity->status;
        if ($oldStatus == 'pending' && $newStatus == OrderStatusEnum::COMPLETE->value) {
            $identity->user->update([
                'is_verified' => true
            ]);
            $data  = [
                'title' => 'طلب توثيق الحساب',
                'body' => 'تم توثيق حسابك بنجاح'
            ];
            SendNotifyHelper::sendNotifyMultiUser(collect([$identity->user]), $data);
        } else if ($oldStatus != OrderStatusEnum::CANCELED->value && $newStatus == OrderStatusEnum::CANCELED->value) {
            $identity->user->update([
                'is_verified' => false
            ]);
            $data  = [
                'title' => 'طلب توثيق الحساب',
                'body' => 'تم رفض توثيق حسابك يرجى رفع صورة أوضح للهوية'
            ];
            SendNotifyHelper::sendNotifyMultiUser(collect([$identity->user]), $data);
        }
    }

    /**
     * Handle the Identity "deleted" event.
     */
    public function deleted(Identity $identity): void
    {
        //
    }

    /**
     * Handle the Identity "restored" event.
     */
    public function restored(Identity $identity): void
    {
        //
    }

    /**
     * Handle the Identity "force deleted" event.
     */
    public function forceDeleted(Identity $identity): void
    {
        //
    }
}
