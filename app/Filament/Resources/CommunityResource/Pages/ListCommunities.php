<?php

namespace App\Filament\Resources\CommunityResource\Pages;

use App\Filament\Resources\CommunityResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCommunities extends ListRecords
{
    protected static string $resource = CommunityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'القنوات' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->channel()),
            'المجموعات' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->group()),
            'المحادثات' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->chat())
        ];
    }
}
