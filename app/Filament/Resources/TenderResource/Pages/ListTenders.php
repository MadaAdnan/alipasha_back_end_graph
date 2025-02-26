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

    protected function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()->with('category','user','sub1','city'); // TODO: Change the autogenerated stub
    }

    public function getTabs(): array
    {
        return [
            'المناقصات' => Tab::make()->modifyQueryUsing(fn(Builder $query) => $query->notPending()),

            'بإنتظار التفعيل' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->pending())->badge(fn() => Product::tender()->pending()->count()),
            'المناقصات  النشطة' => Tab::make()->modifyQueryUsing(fn(Builder $query) => $query->notPending()->where('end_date','>',now())),
            'سلة المحذوفات' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->onlyTrashed())->badge(fn() => Product::tender()->onlyTrashed()->count())
        ];
    }
}
