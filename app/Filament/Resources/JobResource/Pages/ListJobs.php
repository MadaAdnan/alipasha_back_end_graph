<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Enums\CategoryTypeEnum;
use App\Filament\Resources\JobResource;
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
            'الكل' => Tab::make(),
            'باحثين عن وظائف' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', CategoryTypeEnum::SEARCH_JOB->value)),
            'شواغر وظيفية' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', CategoryTypeEnum::JOB->value)),
        ];
    }
}
