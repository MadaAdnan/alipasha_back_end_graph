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
        $users=User::select(['id','is_seller','email_verified_at'])/*->whereBetween('created_at',[$start,$end])*/->get();
        $products=Product::select(['id','type'])->where('active',ProductActiveEnum::ACTIVE->value)/*->whereBetween('created_at',[$start,$end])*/->get();
        return [
            Stat::make('عدد المستخدمين الموثقين', $users->where('is_seller','=',0)->whereNotNull('email_verified_at')->count()),
            Stat::make('عدد المستخدمين غير الموثقين', $users->where('is_seller','=',0)->whereNull('email_verified_at')->count()),
            Stat::make('عدد المتاجر ', $users->where('is_seller','=',1)->count()),
            Stat::make('عدد المنتجات ', $products->whereIn('type',[
                CategoryTypeEnum::RESTAURANT->value,
                CategoryTypeEnum::PRODUCT->value,
            ])->count()),
            Stat::make('عدد المناقصات', $products->where('type','=', CategoryTypeEnum::TENDER->value)->count()),
            Stat::make('عدد الوظائف', $products->where('type','=', CategoryTypeEnum::JOB->value)->count()),
            Stat::make('عدد طلبات التوظيف', $products->where('type','=', CategoryTypeEnum::SEARCH_JOB->value)->count()),
            Stat::make('عدد الأخبار', $products->where('type','=', CategoryTypeEnum::NEWS->value)->count()),
            Stat::make('عدد الخدمات', $products->where('type','=', CategoryTypeEnum::SERVICE->value)->count()),
        ];
    }
}
