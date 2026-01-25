<?php

namespace App\Filament\Resources\ClickWhatsResource\Pages;

use App\Filament\Resources\ClickWhatsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClickWhats extends ListRecords
{
    protected static string $resource = ClickWhatsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
