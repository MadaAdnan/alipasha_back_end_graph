<?php

namespace App\Filament\Resources;

use App\Enums\PartnerTypeEnum;
use App\Filament\Resources\SellerResource\Pages;
use App\Filament\Resources\SellerResource\RelationManagers;
use App\Models\City;
use App\Models\Partner;
use App\Models\Seller;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SellerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $slug = 'sellers';
    protected static ?string $navigationLabel = 'التجار';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('التجار')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->conversion('webp')->imageCropAspectRatio('1:1')
                        ->image()->label('لوغو المتجر'),
                    Forms\Components\Select::make('city_id')->options(City::orderBy('name')->pluck('name', 'id'))->searchable()->label('المدينة'),
                    Forms\Components\TextInput::make('name')->label('اسم التاجر'),
                    Forms\Components\TextInput::make('address')->label('عنوان التاجر'),
                    Forms\Components\TextInput::make('phone')->label('رقم التاجر'),
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('type', PartnerTypeEnum::SELLER->value))
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image')->conversion('webp')->label('صورة لمتجر')->circular(),
                Tables\Columns\TextColumn::make('name')->label('اسم التاجر')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('رقم التاجر'),
                Tables\Columns\TextColumn::make('city.name')->label('المدينة'),
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
            'index' => Pages\ListSellers::route('/'),
            'create' => Pages\CreateSeller::route('/create'),
            'edit' => Pages\EditSeller::route('/{record}/edit'),
        ];
    }
}
