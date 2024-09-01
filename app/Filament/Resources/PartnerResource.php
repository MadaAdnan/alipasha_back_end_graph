<?php

namespace App\Filament\Resources;

use App\Enums\PartnerTypeEnum;
use App\Filament\Resources\PartnerResource\Pages;
use App\Filament\Resources\PartnerResource\RelationManagers;
use App\Models\City;
use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'الوكلاء';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الوكلاء')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('logo')->conversion('webp')->imageCropAspectRatio('1:1')
                        ->image()->label('لوغو المتجر'),
                    Forms\Components\Select::make('city_id')->options(City::orderBy('name')->pluck('name', 'id'))->searchable()->label('المدينة'),
                    Forms\Components\TextInput::make('name')->label('اسم الوكيل'),
                    Forms\Components\TextInput::make('address')->label('عنوان الوكيل'),
                    Forms\Components\TextInput::make('phone')->label('رقم الوكيل'),
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('type', PartnerTypeEnum::PARTNER->value))
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('اسم الوكيل')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('رقم الوكيل'),
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
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
