<?php

namespace App\Filament\Resources\LoginSettingResource\Pages;

use App\Filament\Resources\LoginSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoginSetting extends EditRecord
{
    protected static string $resource = LoginSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
