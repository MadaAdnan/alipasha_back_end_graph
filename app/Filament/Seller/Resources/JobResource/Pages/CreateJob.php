<?php

namespace App\Filament\Seller\Resources\JobResource\Pages;

use App\Enums\CategoryTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Filament\Seller\Resources\JobResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id']=auth()->id();
        $data['active']=ProductActiveEnum::PENDING->value;
        $data['level']=LevelProductEnum::NORMAL->value;

        $data['expert'] = \Str::words(strip_tags(html_entity_decode($data['info'])), 15);

        return parent::mutateFormDataBeforeCreate($data); // TODO: Change the autogenerated stub
    }
}
