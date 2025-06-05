<?php

namespace App\Filament\Seller\Widgets;

use App\Enums\ProductActiveEnum;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort=1;
    protected function getStats(): array
    {
        $product=Product::product()->where('user_id',auth()->id())->where('active',ProductActiveEnum::ACTIVE->value)->count();
        $jobs= Product::job()->where('user_id',auth()->id())->where('active',ProductActiveEnum::ACTIVE->value)->count();
        $tenders=Product::tender()->where('user_id',auth()->id())->where('active',ProductActiveEnum::ACTIVE->value)->count();
      $list=[];
      if($product>0){
          $list[]=Stat::make('عدد المنتجات المفعلة',$product );
      }
      if($jobs>0){
          $list[]=Stat::make('عدد الوظائف المفعلة',$jobs);
      }
      if($tenders>0){
          Stat::make('عدد المناقصات المفعلة', $tenders);
      }
      return $list;
    }
}
