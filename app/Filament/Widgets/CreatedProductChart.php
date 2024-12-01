<?php

namespace App\Filament\Widgets;

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
            'week' => 'آخر أسبوع',
            'month' => 'آخر شهر',
            'year' => 'آخر سنة',
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
            $start=now()->subMonth();

        }else if($activeFilter=='year'){
           $start=now()->subYear();

       }
        $data = Trend::model(Product::class)
            ->between(
                start:$start ,
                end: $end,
            )
            ->perMonth()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' =>  $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
