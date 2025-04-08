<?php

namespace App\Filament\Seller\Resources\JobResource\Pages;

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
        $product=Product::find($record);
        abort_if($product->user_id !=auth()->id(),403,'غير مصرح لك بالدخول');
    }
}
