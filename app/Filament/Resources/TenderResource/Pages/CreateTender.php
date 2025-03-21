<?php

namespace App\Filament\Resources\TenderResource\Pages;

use App\Enums\CategoryTypeEnum;
use App\Filament\Resources\TenderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTender extends CreateRecord
{
    protected static string $resource = TenderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['expert'] = \Str::words(strip_tags(html_entity_decode($data['info'])), 15);
        $data['type'] = CategoryTypeEnum::TENDER->value;
        return parent::mutateFormDataBeforeCreate($data); // TODO: Change the autogenerated stub
    }
}
