<?php

namespace App\Filament\Resources\ClickWhatsResource\Pages;

use App\Filament\Resources\ClickWhatsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClickWhats extends EditRecord
{
    protected static string $resource = ClickWhatsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
