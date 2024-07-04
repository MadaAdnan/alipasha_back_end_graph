<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Enums\CategoryTypeEnum;
use App\Filament\Resources\ServiceResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'الخدمات' => Tab::make()->modifyQueryUsing(fn(Builder $query) => $query->notPending()),
            'بإنتظار التفعيل' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->pending())->badge(fn() => Product::service()->pending()->count()),
            'سلة المحذوفات' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->onlyTrashed())->badge(fn() => Product::service()->onlyTrashed()->count())
        ];
    }
}
