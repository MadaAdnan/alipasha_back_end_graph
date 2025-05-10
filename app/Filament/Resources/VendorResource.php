<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Filament\Resources\VendorResource\RelationManagers;
use App\Models\City;
use App\Models\User;
use App\Models\Vendor;
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
    protected static ?string $slug='vendors';

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
                Tables\Columns\SelectColumn::make('city_id')->options(City::where('is_main',1)->pluck('name','id'))->label('المحافظة'),
                Tables\Columns\SelectColumn::make('area_id')->options(City::where('is_main',false)->pluck('name','id'))->label('المدينة')->sortable(),
                Tables\Columns\TextInputColumn::make('phone'),
                Tables\Columns\TextColumn::make('address'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('city_id')->options(City::where('is_main',1)->pluck('name','id'))->label('المحافظة'),
                Tables\Filters\SelectFilter::make('area_id')->options(City::where('is_main',0)->pluck('name','id'))->label('المدينة'),
            ])
            ->actions([
                Tables\Actions\Action::make('whats')->url(fn($record)=>$record->phone!=''?'https://wa.me/'.$record->phone.'?text=يكتب السلام عليكم معك الدعم الفني لتطبيق علي باشا الرجاء إرسال العنوان الدقيق لتحديث بيناتك معرفك هو '.$record->id:"")->label('تواصل واتس'),
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
