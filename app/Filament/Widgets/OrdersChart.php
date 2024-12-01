<?php

namespace App\Filament\Widgets;

use App\Enums\CategoryTypeEnum;
use App\Enums\OrderStatusEnum;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'طلبات الشحن';

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
        $orders = Trend::query(Order::where('status',OrderStatusEnum::COMPLETE->value))
 ->between(
                start:$start ,
                end: $end,
            )
            ->perMonth()
            ->count();
        $invoice = Trend::query(Invoice::where('status',OrderStatusEnum::COMPLETE->value))
            ->between(
                start:$start ,
                end: $end,
            )
            ->perMonth()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => 'طلبات الشحن الخاصة',
                    'data' => $orders->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#1e40af',
                    'borderColor' => '#0000cc',
                ],
                [
                    'label' => 'طلبات الشحن للمنتجات',
                    'data' => $invoice->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#1e40af',
                    'borderColor' => '#0000cc',
                ],

            ],
            'labels' =>  $invoice->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
