<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('طلبات الشحن')->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\TextInput::make('weight')->label('وزن الحمولة'),
                        Forms\Components\TextInput::make('size')->label('حجم الحمولة'),
                    ]),
                    Forms\Components\TextInput::make('height')->label('إرتفاع الحمولة'),
                    Forms\Components\TextInput::make('length')->label('طول الحمولة'),
                    Forms\Components\TextInput::make('width')->label('عرض الحمولة'),
                    Forms\Components\TextInput::make('price')->label('تكلفة الشحن'),
                    Forms\Components\Fieldset::make('معلومات المستلم')->schema([
                        Forms\Components\TextInput::make('receive_name')->label('الاسم'),
                        Forms\Components\TextInput::make('receive_phone')->label('رقم الهاتف'),
                        Forms\Components\TextInput::make('receive_address')->label('العنوان'),
                    ]),
                    Forms\Components\Fieldset::make('معلومات المرسل')->schema([
                        Forms\Components\TextInput::make('sender_name')->label('الاسم'),
                        Forms\Components\TextInput::make('sender_phone')->label('رقم الهاتف'),
                        Forms\Components\TextInput::make('sender_address')->label('العنوان'),
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query)=>$query->latest())
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#'),
                Tables\Columns\TextColumn::make('size')->label('حجم الحمولة'),
                Tables\Columns\TextColumn::make('weight')->label('وزن الحمولة'),
                Tables\Columns\TextColumn::make('from.name')->label('من مدينة'),
                Tables\Columns\TextColumn::make('user.name')->label('مقدم الطلب')->url(fn($record)=>$record->user !=null?UserResource::getUrl('edit',['record'=>$record->user->id]):null),
                Tables\Columns\TextColumn::make('to.name')->label('إلى مدينة'),
                Tables\Columns\TextColumn::make('receive_name')->label('المستلم')->searchable(),
                Tables\Columns\TextColumn::make('receive_phone')->label('هاتف المستلم'),
                Tables\Columns\TextColumn::make('sender_name')->label('المرسل')->searchable(),
                Tables\Columns\TextColumn::make('sender_phone')->label('هاتف المرسل'),
                Tables\Columns\TextColumn::make('status')->label('حالة الطلب'),
                Tables\Columns\TextColumn::make('price')->label('قيمة الطلب'),
                Tables\Columns\TextColumn::make('created_at')->date('Y-m-d')->label('تاريخ الطلب'),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
