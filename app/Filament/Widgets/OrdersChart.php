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
        $ordersComplete = Trend::query(Order::where('status',OrderStatusEnum::COMPLETE->value))
 ->between(
                start:$start ,
                end: $end,
            )
            ->perMonth()
            ->count();
        $invoiceComplete = Trend::query(Invoice::where('status',OrderStatusEnum::COMPLETE->value))
            ->between(
                start:$start ,
                end: $end,
            )
            ->perMonth()
            ->count();
        $ordersCanceled = Trend::query(Order::where('status',OrderStatusEnum::CANCELED->value))
            ->between(
                start:$start ,
                end: $end,
            )
            ->perMonth()
            ->count();
        $invoiceCanceled = Trend::query(Invoice::where('status',OrderStatusEnum::CANCELED->value))
            ->between(
                start:$start ,
                end: $end,
            )
            ->perMonth()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => 'طلبات الشحن الخاصة المكتملة',
                    'data' => $ordersComplete->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#00FF00',
                    'borderColor' => '#00FF00',
                ],
                [
                    'label' => 'طلبات الشحن الخاصة الملغية',
                    'data' => $ordersCanceled->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#FF0000',
                    'borderColor' => '#FF0000',
                ],
                [
                    'label' => 'طلبات الشحن للمنتجات المكتملة',
                    'data' => $invoiceComplete->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#99FF00',
                    'borderColor' => '#99FF00',
                ],
                [
                    'label' => 'طلبات الشحن للمنتجات الملغية',
                    'data' => $invoiceCanceled->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#FF9900',
                    'borderColor' => '#FF9900',
                ],

            ],
            'labels' =>  $invoiceComplete->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
