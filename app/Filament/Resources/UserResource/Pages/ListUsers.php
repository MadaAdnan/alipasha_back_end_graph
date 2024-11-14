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
            'الكل'=>Tab::make('all')->query(fn($query)=>$query)->label('الكل'),
            'محظور'=>Tab::make('block')->query(fn($query)=>$query->where('is_active',0))->label('محظور'),
            'غير مؤكد'=>Tab::make('not_verified')->query(fn($query)=>$query->whereNull('email_verified_at'))->label('غير مؤكد'),
            'متاجر'=>Tab::make('is_seller')->query(fn($query)=>$query->where('is_seller',1))->label('متاجر'),
        ];
    }
}
