<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'الأخبار' =>Tab::make()->modifyQueryUsing(fn(Builder $query) => $query->notPending()),
             'بإنتظار التفعيل' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->pending())->badge(fn() => Product::news()->pending()->count()),
            'سلة المحذوفات' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->onlyTrashed())->badge(fn() => Product::news()->onlyTrashed()->count())
        ];
    }
}
