<?php

namespace App\Filament\Resources\IdentityResource\Pages;

use App\Filament\Resources\IdentityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIdentities extends ListRecords
{
    protected static string $resource = IdentityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
