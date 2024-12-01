<?php

namespace App\Filament\Widgets;

use App\Enums\CategoryTypeEnum;
use App\Models\Product;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class CreatedProductChart extends ChartWidget
{
    protected static ?string $heading = 'إحصائيات المنشورات';

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
        $job = Trend::query(Product::whereIn('type',[
            CategoryTypeEnum::JOB->value,
            CategoryTypeEnum::SEARCH_JOB->value,
        ]))

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
                    'backgroundColor' => '#1e40af',
                    'borderColor' => '#0000cc',
                ],
                [
                    'label' => 'المناقصات',
                    'data' => $tender->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#00bb00',
                    'borderColor' => '#4ade80',
                ],
                [
                    'label' => 'الشواغر وطلبات التوظيف',
                    'data' => $job->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#9d174d',
                    'borderColor' => '#9f1239',
                ],
            ],
            'labels' =>  $products->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
