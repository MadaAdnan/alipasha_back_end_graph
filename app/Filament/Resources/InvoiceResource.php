<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusEnum;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Forms\Components\Section::make('الطلبات')->schema([
                   Forms\Components\TextInput::make('id')->label('رقم الفاتورة')->readOnly(),
                   Forms\Components\Select::make('user_id')->label('المستخدم')->searchable()->getSearchResultsUsing(fn()=>User::whereNotNull('email_verified_at')->selectRaw('name,id')->limit(100)->pluck('name','id')),
                   Forms\Components\Select::make('seller_id')->label('المتجر')->searchable()->getSearchResultsUsing(fn()=>User::whereNotNull('email_verified_at')->selectRaw('seller_name,id')->limit(100)->pluck('seller_name','id')),
               ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#'),
                Tables\Columns\TextColumn::make('user.name')->label('الزبون')->url(fn($record)=>UserResource::getUrl('edit',[$record->user->id]),true),
                Tables\Columns\TextColumn::make('seller.seller_name')->label('المتجر')->url(fn($record)=>UserResource::getUrl('edit',[$record->seller->id]),true),
                Tables\Columns\TextColumn::make('status')->formatStateUsing(fn($state)=>OrderStatusEnum::tryFrom($state)?->getLabel())->icon(fn($state)=>OrderStatusEnum::tryFrom($state)?->getIcon())->color(fn($state)=>OrderStatusEnum::tryFrom($state)?->getColor())->label('حالة الطلب'),
                Tables\Columns\TextColumn::make('total')->label('إجمالي السعر'),
                Tables\Columns\TextColumn::make('shipping')->label('اجور الشحن'),
                Tables\Columns\TextColumn::make('created_at')->since()->label('تاريخ الطلب'),
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
            RelationManagers\ItemsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
