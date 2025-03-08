<?php

namespace App\Observers;

use App\Enums\LevelUserEnum;
use App\Enums\ProductActiveEnum;
use App\Jobs\SendFirebaseNotificationJob;
use App\Models\Product;
use App\Models\User;
use App\Service\SendNotifyHelper;

class ProductObserve
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $data = [];
        if ($product->user != null && ($product->user->is_seller != true || $product->user->level==LevelUserEnum::USER->value)) {
            $data['is_seller'] = true;
            $data['level'] = LevelUserEnum::SELLER->value;
            $data['seller_name'] = $product->user?->name;
            $product->user->update($data);
        }

    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        if ($product->user != null) {
            if ($product->active !== $product->getOriginal('active') && $product->active == ProductActiveEnum::ACTIVE->value) {
                $user = $product->user;
                $data['title'] = 'قبول المنتج';
                $data['body'] = 'تم قبول المنتج  ' . $product->name ?? $product->expert;
                $data['url'] = 'https://ali-pasha.com/product?id=' . $product->id;

                SendNotifyHelper::sendNotify($user, $data);
                /**
                 * send notification for users followers seller
                 */
                $users=User::whereHas('followers',fn($query)=>$query->where('user_id',$product->user_id))->pluck('device_token')->toArray();
                $dataInfo['title']='منشور جديد';
                $dataInfo['body']="قام متجر {$product->user?->seller_name} بإضافة منتج جديد";
                $dataInfo['url'] = 'https://ali-pasha.com/product?id=' . $product->id;
                $job=new SendFirebaseNotificationJob($users,$dataInfo);
                dispatch($job);

            } //
            elseif ($product->active !== $product->getOriginal('active') && $product->active == ProductActiveEnum::BLOCK->value) {
                $user = $product->user;
                $data['title'] = 'حظر المنتج';
                $data['body'] = 'تم حظر المنتج  ' . $product->name ?? $product->expert;
                $data['url'] = 'https://ali-pasha.com/products?id=' . $product->user->id;

                SendNotifyHelper::sendNotify($user, $data);
            } //
            elseif ($product->active === $product->getOriginal('active')) {
                $user = $product->user;
                $data['title'] = 'إصلاح المنتج';
                $data['body'] = 'تم إصلاح المنتج  ' . $product->name ?? $product->expert;
                $data['url'] = 'https://ali-pasha.com/product?id=' . $product->id;

                SendNotifyHelper::sendNotify($user, $data);
            }
        }


    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
