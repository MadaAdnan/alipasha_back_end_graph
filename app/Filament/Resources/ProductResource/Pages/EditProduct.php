<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {

        foreach ($this->record->attributes()->whereHas('attribute', fn($q) => $q->limited())->get() as $attribute) {
            $data['attribute'][$attribute->attribute->id] = $attribute->id;
        }

        foreach ($this->record->attributeProduct()->whereNotNull('value')->get() as $attr) {
            $data['write'][$attr->attribute_id] = $attr->value;
        }
        $array_attr = $this->record->attributes->pluck('id')->toArray();

        foreach (Attribute::multiple()->where('category_id', $this->record->sub1_id)->get() as $attribute) {

            foreach ($attribute->attributes->pluck('id') as $id) {
                $data['options'][$id] = in_array($id, $array_attr) ? true : null;
            }
        }

        return parent::mutateFormDataBeforeFill($data); // TODO: Change the autogenerated stub
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['expert'] = \Str::words(strip_tags(html_entity_decode($data['info'])), 15);

        return parent::mutateFormDataBeforeSave($data); // TODO: Change the autogenerated stub
    }

    /**
     * @var $record Product
     * @var $data []
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $cleanedOptions = [];
        if (isset($data['options'])) {
            $cleanedOptions = array_filter($data['options'], function ($value) {
                return !is_null($value) && $value !== '' && $value;
            });
        }

        $record->update(collect($data)->except('attribute', 'options', 'write')->toArray());

        $attr = array_merge(array_keys($cleanedOptions), $data['attribute']??[]);
        $record->attributes()->sync($attr);

        if (isset($data['write'])) {
            foreach ($data['write'] as $key => $value)
                if (!empty($value)) {
                    $record->attributeProduct()->updateOrCreate(['attribute_id' => $key,], [
                        'value' => $value
                    ]);
                } else {
                    $record->attributeProduct()->where('attribute_products.attribute_id', $key)->delete();
                }
        }
        return $record;
        // return parent::handleRecordUpdate($record, $data); // TODO: Change the autogenerated stub
    }


}
