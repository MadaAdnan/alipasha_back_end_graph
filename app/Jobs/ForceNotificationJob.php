<?php

namespace App\Jobs;

use App\Models\Setting;
use App\Models\User;
use App\Notifications\ForceNotificationNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ForceNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $setting = Setting::first();
        $users100 = User::whereHas('notifications',
            fn($query) => $query->where('created_at', '<', now()->subDays($setting->msg_marketing['notify']))->where(fn($query) => $query->whereNull('data->is_admin')->orWhere('data->is_admin', 1))
        )
            ->whereHas('products', function ($query) {
                $query->where('type',\App\Enums\CategoryTypeEnum::PRODUCT->value);
                $query->orWhere('type',\App\Enums\CategoryTypeEnum::RESTAURANT->value);
            }, '>=', 100)
            ->get();
        $data = ['title' => 'لديك أكثر من 100 منتج', 'body' => $setting->msg_marketing['market_100']];
        \Notification::send($users100, new ForceNotificationNotification($data));


        $users50 = User::whereHas('notifications',
            fn($query) => $query->where('created_at', '<', now()->subDays($setting->msg_marketing['notify']))->where(fn($query) => $query->whereNull('data->is_admin')->orWhere('data->is_admin', 1))
        )
            ->whereHas('products', function ($query) {
                $query->where('type',\App\Enums\CategoryTypeEnum::PRODUCT->value);
                $query->orWhere('type',\App\Enums\CategoryTypeEnum::RESTAURANT->value);
            }, '>=', 50)
            ->get();
        $data = ['title' => 'لديك أكثر من 50 منتج', 'body' => $setting->msg_marketing['market_50']];
        \Notification::send($users50, new ForceNotificationNotification($data));

        $users20 = User::whereHas('notifications',
            fn($query) => $query->where('created_at', '<', now()->subDays($setting->msg_marketing['notify']))->where(fn($query) => $query->whereNull('data->is_admin')->orWhere('data->is_admin', 1))
        )
            ->whereHas('products', function ($query) {
                $query->where('type',\App\Enums\CategoryTypeEnum::PRODUCT->value);
                $query->orWhere('type',\App\Enums\CategoryTypeEnum::RESTAURANT->value);
            }, '>=', 20)
            ->get();
        $data = ['title' => 'لديك أكثر من 20 منتج', 'body' => $setting->msg_marketing['market_20']];
        \Notification::send($users20, new ForceNotificationNotification($data));

        $users6 = User::whereHas('notifications',
            fn($query) => $query->where('created_at', '<', now()->subDays($setting->msg_marketing['notify']))->where(fn($query) => $query->whereNull('data->is_admin')->orWhere('data->is_admin', 1))
        )
            ->whereHas('products', function ($query) {
                $query->where('type',\App\Enums\CategoryTypeEnum::PRODUCT->value);
                $query->orWhere('type',\App\Enums\CategoryTypeEnum::RESTAURANT->value);
            }, '>=', 6)
            ->get();
        $data = ['title' => 'لديك أكثر من 6 منتج', 'body' => $setting->msg_marketing['market_6']];
        \Notification::send($users6, new ForceNotificationNotification($data));

        $users = User::whereHas('notifications',
            fn($query) => $query->where('created_at', '<', now()->subDays($setting->msg_marketing['notify']))->where(fn($query) => $query->whereNull('data->is_admin')->orWhere('data->is_admin', 1))
        )
            ->whereHas('products', function ($query) {
                $query->where('type',\App\Enums\CategoryTypeEnum::PRODUCT->value);
                $query->orWhere('type',\App\Enums\CategoryTypeEnum::RESTAURANT->value);
            }, '=', 0)
            ->get();
        $data = ['title' => 'رسالة ترحيب', 'body' => $setting->msg_marketing['user']];
        \Notification::send($users, new ForceNotificationNotification($data));
    }
}
