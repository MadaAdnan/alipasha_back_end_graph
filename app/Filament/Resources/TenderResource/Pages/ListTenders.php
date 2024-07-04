<?php

namespace App\Filament\Resources\TenderResource\Pages;

use App\Filament\Resources\TenderResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTenders extends ListRecords
{
    protected static string $resource = TenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'المناقصات' => Tab::make()->modifyQueryUsing(fn(Builder $query) => $query->notPending()),
            'بإنتظار التفعيل' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->pending())->badge(fn() => Product::tender()->pending()->count()),
            'سلة المحذوفات' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->onlyTrashed())->badge(fn() => Product::tender()->onlyTrashed()->count())
        ];
    }
}
