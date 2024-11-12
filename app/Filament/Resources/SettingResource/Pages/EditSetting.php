<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Events\ChangeSettingEvent;
use App\Filament\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;




    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


}
