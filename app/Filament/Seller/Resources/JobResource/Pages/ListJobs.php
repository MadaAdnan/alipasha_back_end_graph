<?php

namespace App\Filament\Seller\Resources\JobResource\Pages;

use App\Enums\CategoryTypeEnum;
use App\Filament\Seller\Resources\JobResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListJobs extends ListRecords
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'الكل' => Tab::make()->modifyQueryUsing(fn(Builder $query) => $query->notPending()),
            'باحثين عن وظائف' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', CategoryTypeEnum::SEARCH_JOB->value)->notPending()),
            'شواغر وظيفية' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', CategoryTypeEnum::JOB->value)->notPending()),

            'بإنتظار التفعيل' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->pending())->badge(fn() => Product::job()->pending()->count()),
            'سلة المحذوفات' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->onlyTrashed())->badge(fn() => Product::job()->onlyTrashed()->count())
        ];
    }
}
