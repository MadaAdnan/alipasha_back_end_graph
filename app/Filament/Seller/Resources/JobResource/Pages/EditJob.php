<?php

namespace App\Filament\Seller\Resources\JobResource\Pages;

use App\Enums\CategoryTypeEnum;
use App\Filament\Seller\Resources\JobResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJob extends EditRecord
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);
        $product=Product::where(fn($query)=>
        $query->where('type',CategoryTypeEnum::JOB->value)->orWhere('type',CategoryTypeEnum::SEARCH_JOB->value)
        )->find($record);
        abort_if($product->user_id !=auth()->id(),403,'غير مصرح لك بالدخول');
    }
}
