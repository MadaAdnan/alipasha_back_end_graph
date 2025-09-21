<?php

namespace App\Filament\Resources\JobWorkerResource\Pages;

use App\Filament\Resources\JobWorkerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobWorkers extends ListRecords
{
    protected static string $resource = JobWorkerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
