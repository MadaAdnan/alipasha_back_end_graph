<?php

namespace App\Filament\Resources\LoginSettingResource\Pages;

use App\Filament\Resources\LoginSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoginSettings extends ListRecords
{
    protected static string $resource = LoginSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
