<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
          Stack::make([
              Split::make(fn($record)=>[
                  TextColumn::make('id'),
                  TextColumn::make('status'),
                  TextColumn::make('created_at'),

              ]),
              Split::make(fn($record)=>[
                  TextColumn::make('user.name'),
                  TextColumn::make('phone'),
                  TextColumn::make('address'),
                  TextColumn::make('user.city.name')
              ]),
              Split::make(fn($record)=>[
                  TextColumn::make('seller.name'),
                  TextColumn::make('seller.phone'),
                  TextColumn::make('seller.address'),
                  TextColumn::make('seller.city.name')
              ])
          ])
        ]); // TODO: Change the autogenerated stub
    }
}
