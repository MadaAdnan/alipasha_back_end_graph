<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingPriceResource\Pages;
use App\Filament\Resources\ShippingPriceResource\RelationManagers;
use App\Models\ShippingPrice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShippingPriceResource extends Resource
{
    protected static ?string $model = ShippingPrice::class;

    protected static ?string $navigationIcon = 'fas-hand-holding-dollar';
    protected static ?string $navigationGroup='الشحن';
    protected static ?string $label='أسعار الشحن';
    protected static ?string $navigationLabel='أسعار الشحن';
    protected static ?string $pluralLabel='أسعار الشحن';
    protected static ?int $navigationSort=20;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('أسعار الشحن')->schema([
                    Forms\Components\TextInput::make('weight')->label('الوزن الأعظمي بالكيلو غرام')->required(),
                    Forms\Components\TextInput::make('size')->label('الحجم الاعظمي بالسنتيمتر')->required(),
                    Forms\Components\TextInput::make('internal_price')->numeric()->required()->label('أجور الشحن الداخلي'),
                    Forms\Components\TextInput::make('external_price')->numeric()->required()->label('أجور الشحن الخارجي'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('weight')->label('الوزن الأعظمي '),
                Tables\Columns\TextInputColumn::make('size')->label('الحجم الاعظمي '),
                Tables\Columns\TextInputColumn::make('internal_price')->label('أجور الشحن الداخلي'),
                Tables\Columns\TextInputColumn::make('external_price')->label('أجور الشحن الخارجي'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippingPrices::route('/'),
            'create' => Pages\CreateShippingPrice::route('/create'),
            'edit' => Pages\EditShippingPrice::route('/{record}/edit'),
        ];
    }
}
