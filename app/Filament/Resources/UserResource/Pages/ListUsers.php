<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'الكل'=>Tab::make('all')->query(fn($query)=>$query),
            'محظور'=>Tab::make('all')->query(fn($query)=>$query->where('is_active',0)),
            'غير مؤكد'=>Tab::make('all')->query(fn($query)=>$query->whereNull('email_verified_at')),
        ];
    }
}
