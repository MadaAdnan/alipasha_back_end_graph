<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Filament\Resources\VendorResource\RelationManagers;
use App\Models\City;
use App\Models\User;
use App\Models\Vendor;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VendorResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $slug = 'vendors';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('products_count')->label('المنتجات'),
                Tables\Columns\SelectColumn::make('city_id')->options(City::where('is_main', 1)->orderBy('name')->pluck('name', 'id'))->label('المحافظة'),
                Tables\Columns\SelectColumn::make('area_id')->options(City::where('is_main', false)->orderBy('name')->pluck('name', 'id'))->label('المدينة')->sortable(),
                Tables\Columns\TextColumn::make('phone')->url(fn($record) => $record->phone != '' ? 'https://wa.me/' . $record->phone . '?text= السلام عليكم معك الدعم الفني لتطبيق علي باشا الرجاء إرسال العنوان الدقيق لتحديث بيناتك معرفك هو ' . $record->id : "", true),
                Tables\Columns\TextColumn::make('address')->words(5),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('city_id')->options(City::where('is_main', 1)->orderBy('name')->pluck('name', 'id'))->label('المحافظة'),
                Tables\Filters\SelectFilter::make('area_id')->options(City::where('is_main', 0)->orderBy('name')->pluck('name', 'id'))->label('المدينة'),
            ])
            ->actions([
              //  Tables\Actions\Action::make('whats')->url(fn($record) => $record->phone != '' ? 'https://wa.me/' . $record->phone . '?text= السلام عليكم معك الدعم الفني لتطبيق علي باشا الرجاء إرسال العنوان الدقيق لتحديث بيناتك معرفك هو ' . $record->id : "", true)->label('تواصل واتس'),
               Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}
