<?php

namespace App\Filament\Seller\Resources\TenderResource\Pages;

use App\Filament\Seller\Resources\TenderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTender extends EditRecord
{
    protected static string $resource = TenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
