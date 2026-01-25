<?php

namespace App\Filament\Resources\MessageMarketingResource\Pages;

use App\Filament\Resources\MessageMarketingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMessageMarketings extends ListRecords
{
    protected static string $resource = MessageMarketingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
