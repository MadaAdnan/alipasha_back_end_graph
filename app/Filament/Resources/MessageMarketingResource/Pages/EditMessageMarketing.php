<?php

namespace App\Filament\Resources\MessageMarketingResource\Pages;

use App\Filament\Resources\MessageMarketingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMessageMarketing extends EditRecord
{
    protected static string $resource = MessageMarketingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
