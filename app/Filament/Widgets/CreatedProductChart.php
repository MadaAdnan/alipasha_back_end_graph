<?php

namespace App\Filament\Widgets;

use App\Enums\CategoryTypeEnum;
use App\Models\Product;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class CreatedProductChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'اليوم',
            'week' => 'آخر 7 أيام',
            'month' => 'هذا الشهر',
            'year' => 'هذه السنة',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
       $start=now()->startOfDay();
       $end=now();
       if($activeFilter=='week'){
           $start=now()->subDays(7);

       }else if($activeFilter=='month'){
            $start=now()->startOfMonth();

        }else if($activeFilter=='year'){
           $start=now()->startOfYear();

       }else if($activeFilter=='today'){
           $start=now()->startOfDay();

       }
        $products = Trend::query(Product::whereIn('type',[
            CategoryTypeEnum::PRODUCT->value,
            CategoryTypeEnum::RESTAURANT->value,
            ]))

            ->between(
                start:$start ,
                end: $end,
            )
            ->perMonth()
            ->count();
        $tender = Trend::query(Product::where('type',CategoryTypeEnum::TENDER->value))

            ->between(
                start:$start ,
                end: $end,
            )
            ->perMonth()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => 'المنتجات',
                    'data' => $products->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
                [
                    'label' => 'المناقصات',
                    'data' => $tender->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#63B4EB',
                    'borderColor' => '#B9F2F5',
                ],
            ],
            'labels' =>  $products->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
