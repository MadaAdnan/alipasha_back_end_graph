<?php

namespace App\Filament\Resources\IdentityResource\Pages;

use App\Enums\IdentityEnum;
use App\Enums\OrderStatusEnum;
use App\Filament\Resources\IdentityResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
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
    public function getTabs(): array
    {
        return [

            Tab::make( IdentityEnum::PENDING->value)->modifyQueryUsing(fn($query)=>$query->where('status',IdentityEnum::PENDING->value)->with(['user']))->label(IdentityEnum::PENDING->getLabel()),
            Tab::make( IdentityEnum::COMPLETE->value)->modifyQueryUsing(fn($query)=>$query->where('status',IdentityEnum::COMPLETE->value)->with(['user']))->label(IdentityEnum::COMPLETE->getLabel()),
            Tab::make( IdentityEnum::CANCELE->value)->modifyQueryUsing(fn($query)=>$query->where('status',IdentityEnum::CANCELE->value)->with(['user']))->label(IdentityEnum::CANCELE->getLabel()),


        ];
    }
}
