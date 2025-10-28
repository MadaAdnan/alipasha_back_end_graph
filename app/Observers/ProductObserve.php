<?php

namespace App\Observers;

use App\Enums\CategoryTypeEnum;
use App\Enums\LevelUserEnum;
use App\Enums\ProductActiveEnum;
use App\Jobs\SendFirebaseNotificationJob;
use App\Jobs\WebhokProductAi;
use App\Jobs\WebhokProductJob;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Service\SendNotifyHelper;
use Mockery\Exception;

class ProductObserve
{

    public function creating(Product $product): void
    {
        if ($product->type != CategoryTypeEnum::PRODUCT->value && $product->type != CategoryTypeEnum::RESTAURANT->value) {
            $product->power = rand(20, 100);
        }
        if ($product->phone == null) {
            $product->phone = $product->user?->phone;
        }

    }

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $data = [];
        if ($product->user != null && ($product->user->is_seller != true || $product->user->level == LevelUserEnum::USER->value)) {
            $data['is_seller'] = true;
            $data['level'] = LevelUserEnum::SELLER->value;
            $data['seller_name'] = $product->user?->name;

        }

        $product->user->update($data);
        if ($product->active == ProductActiveEnum::ACTIVE->value) {
            /**
             * send notification for users followers seller
             */
            $usersIds = \DB::table('user_follow')->where('seller_id', $product->user_id)->select('user_id')->pluck('user_id')->toArray();
            $users = User::whereIn('id', $usersIds)->pluck('device_token')->toArray();
            $dataInfo['title'] = 'منشور جديد';
            $dataInfo['body'] = "قام متجر {$product->user?->seller_name} بإضافة منتج جديد";
            $dataInfo['url'] = 'https://ali-pasha.com/product?id=' . $product->id;
            $job = new SendFirebaseNotificationJob($users, $dataInfo);
            dispatch($job);
        }else{
            $job=new WebhokProductAi($product);
            dispatch($job);
        }

    }

    public function updating(Product $product): void
    {
        $product->is_sync_webhok = false;
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        if ($product->user != null) {
            if (\Str::isUrl($product->user->url_webhok)) {
                try{
                    $job = new WebhokProductJob($product, 'update');
                    dispatch($job);
                }catch (Exception | \Error $exception){
                    \Log::error("WebhokProductJob".$exception->getMessage());
                }
            }
            if ($product->active !== $product->getOriginal('active') && $product->active == ProductActiveEnum::ACTIVE->value) {
                $user = $product->user;
                $data['title'] = 'قبول المنتج';
                $data['body'] = 'تم قبول المنتج  ' . $product->name ?? $product->expert;
                $data['url'] = 'https://ali-pasha.com/product?id=' . $product->id;
 try{
                SendNotifyHelper::sendNotify($user, $data);
                 }catch (Exception | \Error $exception){
                    \Log::error("SendNotifyHelper".$exception->getMessage());
                }
                /**
                 * send notification for users followers seller
                 */
                $usersIds = \DB::table('user_follow')->where('seller_id', $product->user_id)->select('user_id')->pluck('user_id')->toArray();
                $users = User::whereIn('id', $usersIds)->pluck('device_token')->toArray();
                $dataInfo['title'] = 'منشور جديد';
                $dataInfo['body'] = "قام متجر {$product->user?->seller_name} بإضافة منتج جديد";
                $dataInfo['url'] = 'https://ali-pasha.com/product?id=' . $product->id;
                 try{
                $job2 = new SendFirebaseNotificationJob($users, $dataInfo);
                dispatch($job2);
                 }catch (Exception | \Error $exception){
                     \Log::error("SendFirebaseNotificationJob".$exception->getMessage());
                 }

            } //
            elseif ($product->active !== $product->getOriginal('active') && $product->active == ProductActiveEnum::BLOCK->value) {
                $user = $product->user;
                $data['title'] = 'حظر المنتج';
                $data['body'] = 'تم حظر المنتج  ' . $product->name ?? $product->expert;
                if ($product->block_msg != '') {
                    $data['body'] .= "السبب : {$product->block_msg}";
                }
                $data['url'] = 'https://ali-pasha.com/products?id=' . $product->user->id;
                info('test block');
                SendNotifyHelper::sendNotify($user, $data);
            } //
            elseif ($product->active === $product->getOriginal('active')) {
                $user = $product->user;
                $data['title'] = 'إصلاح المنتج';
                $data['body'] = 'تم إصلاح المنتج  ' . $product->name ?? $product->expert;
                $data['url'] = 'https://ali-pasha.com/product?id=' . $product->id;

                SendNotifyHelper::sendNotify($user, $data);
            }
            $setting=Setting::first();
            if($product->active==ProductActiveEnum::PENDING->value && $setting->is_active_ai){
                  try{
                $job=new WebhokProductAi($product);
                dispatch($job);
                 }catch (Exception | \Error $exception){
                     \Log::error("WebhokProductAi".$exception->getMessage());
                 }
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
