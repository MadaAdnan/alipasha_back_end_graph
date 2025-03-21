<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['expert'] = \Str::words(strip_tags(html_entity_decode($data['info'])), 15);

        return parent::mutateFormDataBeforeCreate($data); // TODO: Change the autogenerated stub
    }

    protected function handleRecordCreation(array $data): Model
    {
        $cleanedOptions = [];
        if (isset($data['options'])) {
            $cleanedOptions = array_filter($data['options'], function ($value) {
                return !is_null($value) && $value !== '';
            });
        }


        $attr = array_merge(array_keys($cleanedOptions), $data['attribute']??[]);
        /**
         * @var $record Product
         */
        //dd($data['options'],$cleanedOptions);
        $record = parent::handleRecordCreation(collect($data)->except('attribute', 'options', 'write')->toArray());

        $record->attributes()->sync($attr);
        if (isset($data['write'])) {
            foreach ($data['write'] as $key => $value) {
                if (!empty($value)) {
                    $record->attributeProduct()->create([
                        'attribute_id' => $key,
                        'value' => $value
                    ]);
                }

            }
        }


        return $record;
    }
}
