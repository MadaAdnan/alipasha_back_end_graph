<?php

namespace App\Filament\Resources\ShamCashResource\Pages;

use App\Filament\Resources\ShamCashResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShamCashes extends ListRecords
{
    protected static string $resource = ShamCashResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
