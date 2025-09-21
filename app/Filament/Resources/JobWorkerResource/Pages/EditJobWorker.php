<?php

namespace App\Filament\Resources\JobWorkerResource\Pages;

use App\Filament\Resources\JobWorkerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobWorker extends EditRecord
{
    protected static string $resource = JobWorkerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
