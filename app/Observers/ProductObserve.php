<?php

namespace App\Observers;

use App\Enums\ProductActiveEnum;
use App\Models\Product;
use App\Service\SendNotifyHelper;

class ProductObserve
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        if ($product->user != null && $product->user->is_seller != true) {
            $product->user->update(['is_seller' => true, 'seller_name' => $product->user?->name]);
        }
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        if($product->user!=null){
            if ($product->active !== $product->getOriginal('active') && $product->active == ProductActiveEnum::ACTIVE->value) {
                $user = $product->user;
                $data['title'] = 'قبول المنتج';
                $data['body'] = 'تم قبول المنتج  ' . $product->name ?? $product->expert;
                $data['url'] = 'https://ali-pasha.com/product?id=' . $product->id;

                SendNotifyHelper::sendNotify($user, $data);
            }
            //
            elseif ($product->active !== $product->getOriginal('active') && $product->active == ProductActiveEnum::BLOCK->value) {
                $user = $product->user;
                $data['title'] = 'حظر المنتج';
                $data['body'] = 'تم حظر المنتج  ' . $product->name ?? $product->expert;
                $data['url'] = 'https://ali-pasha.com/products?id=' . $product->user->id;

                SendNotifyHelper::sendNotify($user, $data);
            }
            //
            elseif($product->active === $product->getOriginal('active')){
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
