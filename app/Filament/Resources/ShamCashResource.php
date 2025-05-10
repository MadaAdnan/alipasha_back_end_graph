<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShamCashResource\Pages;
use App\Filament\Resources\ShamCashResource\RelationManagers;
use App\Models\Setting;
use App\Models\ShamCash;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShamCashResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $slug = 'sham-cash';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات شام كاش')->schema([
                    Forms\Components\TextInput::make('wallet')->nullable()->label('رقم الحساب في شام كاش'),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('sham-cash')->collection('sham-cash')->conversion('webp')->label('QR شام كاش')->image()->imageCropAspectRatio('1:1')->imageEditor(),

                ])->visible(fn()=>auth()->user()->can('sham_cash_setting')),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListShamCashes::route('/'),
            'create' => Pages\CreateShamCash::route('/create'),
            'edit' => Pages\EditShamCash::route('/{record}/edit'),
        ];
    }
}
