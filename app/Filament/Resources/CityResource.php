<?php

namespace App\Filament\Resources;

use App\Enums\IsActiveEnum;
use App\Filament\Resources\CityResource\Pages;
use App\Filament\Resources\CityResource\RelationManagers;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 9;
    protected static ?string $label = 'مدينة';
    protected static ?string $modelLabel = 'مدينة';
    protected static ?string $navigationLabel = 'المدن';
    protected static ?string $pluralLabel = 'المدن';

    protected static ?string $navigationGroup='المناطق و المواد';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المدن')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->conversion('webp')->image()->imageCropAspectRatio('1:1')->label('صورة')->imageEditor(),
                    Forms\Components\TextInput::make('name')->label('اسم المدينة'),

                    Forms\Components\Select::make('city_id')->options(City::where('is_main', true)->pluck('name', 'id'))->required()->label('تتبع لمدينة'),
                    Forms\Components\Toggle::make('is_delivery')->label('تفعيل التوصيل'),
                    Forms\Components\Toggle::make('is_active')->label('حالة المدينة'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image')->circular()->label('صورة'),
                Tables\Columns\TextColumn::make('name')->label('المدينة')->searchable(),
                Tables\Columns\TextColumn::make('city.name')->label('المدينة الرئيسية')->sortable(),
                Tables\Columns\TextColumn::make('is_active')->formatStateUsing(fn($state)=>IsActiveEnum::tryFrom($state)?->getLabel())->icon(fn($state)=>IsActiveEnum::tryFrom($state)?->getIcon())->color(fn($state)=>IsActiveEnum::tryFrom($state)?->getColor())->label('الحالة'),
            ])->reorderable('sortable')
            ->filters([
                Tables\Filters\SelectFilter::make('city_id')->options(City::where('is_main',true)->pluck('name','id'))->label('المدينة')
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
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }
}
