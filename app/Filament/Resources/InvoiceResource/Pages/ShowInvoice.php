<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Enums\OrderStatusEnum;
use App\Filament\Resources\InvoiceResource;
use App\Filament\Resources\UserResource;
use App\Models\Product;
use Filament\Actions;

use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ShowInvoice extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getTableQuery(): ?Builder
    {

        return parent::getTableQuery()->latest(); // TODO: Change the autogenerated stub
    }

    public function table(Table $table): Table
    {
        return $table->columns([

            Panel::make([
                Split::make([
                    TextColumn::make('id'),
                    TextColumn::make('status')->formatStateUsing(fn($state)=>OrderStatusEnum::tryFrom($state)?->getLabel())->color(fn($state)=>OrderStatusEnum::tryFrom($state)?->getColor()),
                    TextColumn::make('created_at')->date('y-m-d'),
                ]),
                View::make('invoice.table.seller'),
//                View::make('invoice.table.user'),
                View::make('invoice.table.items'),
            ])/*->collapsible()->collapsed(fn($record)=>$record->status!='pending')*/

        ]); // TODO: Change the autogenerated stub
    }
}
