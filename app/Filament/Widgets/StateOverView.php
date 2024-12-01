<?php

namespace App\Filament\Widgets;

use App\Enums\CategoryTypeEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StateOverView extends BaseWidget
{

    protected function getStats(): array
    {
        $start=now()->subDays(7);
        $end=now();
        $users=User::select(['id','is_seller'])->whereBetween('created_at',[$start,$end])->get();
        $products=Product::select(['id','type'])->where('active',ProductActiveEnum::ACTIVE->value)->whereBetween('created_at',[$start,$end])->get();
        return [
            Stat::make('المستخدمين الجدد خلال آخر 7أيام', $users->where('is_seller','=',0)->count()),
            Stat::make('عدد المتاجر المنضمة خلال آخر 7 أيام', $users->where('is_seller','=',1)->count()),
            Stat::make('عدد المنتجات المضافة خلال آخر 7 أيام', $products->whereIn('type',[
                CategoryTypeEnum::RESTAURANT->value,
                CategoryTypeEnum::PRODUCT->value,
            ])->count()),
            Stat::make('عدد المناقصات المضافة خلال آخر 7 أيام', $products->where('type','=', CategoryTypeEnum::TENDER->value)->count()),
            Stat::make('عدد الوظائف المضافة خلال آخر 7 أيام', $products->where('type','=', CategoryTypeEnum::JOB->value)->count()),
            Stat::make('عدد طلبات التوظيف المضافة خلال آخر 7 أيام', $products->where('type','=', CategoryTypeEnum::SEARCH_JOB->value)->count()),
            Stat::make('عدد الأخبار المضافة خلال آخر 7 أيام', $products->where('type','=', CategoryTypeEnum::NEWS->value)->count()),
            Stat::make('عدد الخدمات المضافة خلال آخر 7 أيام', $products->where('type','=', CategoryTypeEnum::SERVICE->value)->count()),
        ];
    }
}
