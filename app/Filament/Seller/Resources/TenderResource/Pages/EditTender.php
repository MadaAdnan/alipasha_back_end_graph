<?php

namespace App\Filament\Seller\Resources\TenderResource\Pages;

use App\Filament\Seller\Resources\TenderResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTender extends EditRecord
{
    protected static string $resource = TenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record); parent::mount($record);
        $product=Product::find($record);
        abort_if($product->user_id !=auth()->id(),403,'غير مصرح لك بالدخول');

    }

}
