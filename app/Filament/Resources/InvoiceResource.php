<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusEnum;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
                    Forms\Components\TextInput::make('id')->label('رقم الفاتورة')->readOnly(fn($context)=>$context=='edit'),
                    Forms\Components\Select::make('user_id')->relationship('user','name')->label('المستخدم')->searchable()->dehydrated(fn($context)=>$context=='edit'),
                    Forms\Components\Select::make('seller_id')->relationship('seller','seller_name')->label('المتجر')->searchable()->dehydrated(fn($context)=>$context=='edit'),

               Forms\Components\TextInput::make('phone')->readOnly(fn($context)=>$context=='edit')->label('الهاتف'),
               Forms\Components\TextInput::make('address')->readOnly(fn($context)=>$context=='edit')->label('العنوان'),
                    Forms\Components\TextInput::make('total')->numeric()->readOnly(fn($context)=>$context=='edit')->label('إجمالي قيمة البضاعة'),
               Forms\Components\TextInput::make('shipping')->numeric()->label('إجمالي أجور الشحن'),
              Forms\Components\Repeater::make('items')->relationship('items')->schema([
                  Forms\Components\Grid::make()->schema([
                      Forms\Components\Select::make('product_id')->options(fn($context,Model $record)=>Product::where('type','product')
                          ->when($context=='edit',fn($query)=>$query->where('user_id',$record->user_id))
                          ->selectRaw('products.id,products.name')->pluck('name','id')->toArray())->searchable()->label('المنتج')->required(),
                      Forms\Components\TextInput::make('qty')->label('الكمية')->required()
                  ])
              ])->label('المنتجات')->minItems(1)->required()
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(true)
            ->paginatedWhileReordering(false)
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#'),
                Tables\Columns\TextColumn::make('user.name')->label('الزبون')->url(fn($record) => UserResource::getUrl('edit', [$record->user->id]), true),
                Tables\Columns\TextColumn::make('seller.seller_name')->label('المتجر')->url(fn($record) => UserResource::getUrl('edit', [$record->seller->id]), true),
                Tables\Columns\TextColumn::make('status')->formatStateUsing(fn($state) => OrderStatusEnum::tryFrom($state)?->getLabel())->icon(fn($state) => OrderStatusEnum::tryFrom($state)?->getIcon())->color(fn($state) => OrderStatusEnum::tryFrom($state)?->getColor())->label('حالة الطلب'),

                Tables\Columns\TextColumn::make('seller_note')->label('ملاحظات التاجر'),
                Tables\Columns\TextColumn::make('total')->label('إجمالي السعر'),
                Tables\Columns\TextColumn::make('shipping')->label('اجور الشحن'),
                Tables\Columns\TextColumn::make('address')->label('عنوان الشحن'),
                Tables\Columns\TextColumn::make('phone')->label('رقم الهاتف'),
                Tables\Columns\TextColumn::make('created_at')->since()->label('تاريخ الطلب'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')->searchable()->getSearchResultsUsing(fn($search) => User::where('name', 'like', "%$search%")->take(7)->pluck('name', 'id'))->label('الزبون'),
                Tables\Filters\SelectFilter::make('seller_id')->searchable()->getSearchResultsUsing(fn($search) => User::where('is_seller', 1)->where('name', 'like', "%$search%")->take(7)->pluck('name', 'id'))->label('التاجر')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('take')->requiresConfirmation()->action(fn($record) => $record->update(['status' => OrderStatusEnum::AWAY->value]))->label('تأكيد إستلام البضاعة من التاجر')->visible(fn($record) => $record->status == OrderStatusEnum::AGREE->value),
                    Tables\Actions\Action::make('agree')->requiresConfirmation()->action(fn($record) => $record->update(['status' => OrderStatusEnum::AGREE->value]))->label('تأكيد موافقة التاجر')->visible(fn($record) => $record->status == OrderStatusEnum::PENDING->value),
                     Tables\Actions\Action::make('complete')->requiresConfirmation()->action(fn($record) => $record->update(['status' => OrderStatusEnum::COMPLETE->value]))->label('تأكيد تسليم الطلب للزبون')->visible(fn($record) => $record->status == OrderStatusEnum::AWAY->value),
                     Tables\Actions\Action::make('cancel')->requiresConfirmation()->action(fn($record) => $record->update(['status' => OrderStatusEnum::CANCELED->value]))->label('تأكيد إلغاء الطلب')->visible(fn($record) => $record->status != OrderStatusEnum::COMPLETE->value && $record->status != OrderStatusEnum::CANCELED->value),
                ])
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
           // RelationManagers\ItemsRelationManager::class
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
