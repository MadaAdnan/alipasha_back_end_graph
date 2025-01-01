<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

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
          Stack::make([
            Panel::make([
                Grid::make(4)->schema([
                    TextColumn::make('id'),
                    TextColumn::make('status'),
                    TextColumn::make('created_at'),

                ]),
            ]),
            Panel::make([

                 Grid::make(4)->schema([
                     TextColumn::make('user.name')->url(fn($record)=>UserResource::getUrl('edit',['record'=>$record->user?->id])),
                     TextColumn::make('phone')->url(fn($state)=>'https://wa.me/'.$state),
                     TextColumn::make('address'),
                     TextColumn::make('user.city.name')
                 ])

            ])->columnSpanFull(),
             Panel::make([
                 Grid::make(4)->schema([
                     TextColumn::make('seller.name')->url(fn($record)=>UserResource::getUrl('edit',['record'=>$record->seller?->id]))->columnSpan(1),
                     TextColumn::make('seller.phone')->url(fn($state)=>'https://wa.me/'.$state),
                     TextColumn::make('seller.address'),
                     TextColumn::make('seller.city.name')
                 ]),
             ])->columnSpanFull(),
              Split::make([
                  View::make('invoice.table.items'),
              ])
          ])
        ]); // TODO: Change the autogenerated stub
    }
}
