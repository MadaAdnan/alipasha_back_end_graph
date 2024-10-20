<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'المنتجات' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->notPending()),
            'بإنتظار التفعيل' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->pending())->badge(fn () => Product::product()->pending()->count()),
            'سلة المحذوفات' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed())->badge(fn () => Product::product()->onlyTrashed()->count())
        ];
    }


}
