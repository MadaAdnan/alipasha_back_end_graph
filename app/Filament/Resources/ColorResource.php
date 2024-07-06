<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ColorResource\Pages;
use App\Filament\Resources\ColorResource\RelationManagers;
use App\Models\Color;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ColorResource extends Resource
{
    protected static ?string $model = Color::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label='لون';
    protected static ?string $modelLabel='لون';
    protected static ?string $navigationLabel='الألوان';
    protected static ?string $pluralLabel='الألوان';
    protected static ?int $navigationSort = 22;
    protected static ?string $navigationGroup = 'خصائص / خيارات';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الألوان')->schema([
                    Forms\Components\TextInput::make('name')->label('اسم اللون'),
                    Forms\Components\ColorPicker::make('code')->label(' اللون'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('اسم اللون')->searchable(),
                Tables\Columns\ColorColumn::make('code')->label('اللون'),
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
            'index' => Pages\ListColors::route('/'),
            'create' => Pages\CreateColor::route('/create'),
            'edit' => Pages\EditColor::route('/{record}/edit'),
        ];
    }
}
