<?php

namespace App\Filament\Widgets;

use App\Enums\CategoryTypeEnum;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class RegisteredUserChart extends ChartWidget
{
    protected static ?string $heading = 'المسجلين الجدد';

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
        $users = Trend::model(User::class)
 ->between(
                start:$start ,
                end: $end,
            )
            ->perMonth()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => 'المسجلين الجدد',
                    'data' => $users->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#1e40af',
                    'borderColor' => '#0000cc',
                ],

            ],
            'labels' =>  $users->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
