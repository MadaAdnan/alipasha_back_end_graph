<?php

namespace App\Filament\Seller\Widgets;

use App\Models\ProductView;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AccessbilityPostsChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {

        $data = Trend::query(ProductView::whereHas('product', fn($query) => $query->where('products.user_id', auth()->id())))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('count');

        return [
            'datasets' => [
                [
                    'label' => 'وصول المنشورات',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];

    }

    protected function getType(): string
    {
        return 'bar';
    }
}
