<?php

namespace App\Filament\Resources;

use App\Enums\ProductActiveEnum;
use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Helpers\HelperMedia;
use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'خدمة';
    protected static ?string $modelLabel = 'خدمة';
    protected static ?string $navigationLabel = 'الخدمات';
    protected static ?string $pluralLabel = 'الخدمات';
    protected static ?int $navigationSort = -15;
    protected static ?string $navigationGroup = 'العروض';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الخدمات')->schema([
                    HelperMedia::getFileUpload(),
                    Forms\Components\Select::make('user_id')->options(User::seller()->pluck('users.seller_name', 'users.id'))->label('المتجر')->live()->afterStateUpdated(fn($set, $state) => $set('city_id', User::find($state)?->city_id)),
                    Forms\Components\Select::make('city_id')->options(City::pluck('name', 'id'))->searchable()->label('المدينة'),
                    Forms\Components\TextInput::make('name')->label('اسم الخدمة'),
                    Forms\Components\Textarea::make('info')->label('وصف الخدمة'),
                    Forms\Components\TagsInput::make('tags')->suggestions(fn() => Product::service()->pluck('tags')->flatten()->unique())->label('تاغات'),
                    Forms\Components\Select::make('category_id')->options(Category::service()->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live()->required(),
                    Forms\Components\Select::make('sub1_id')->options(fn($get) => Category::find($get('category_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),

                    Forms\Components\TextInput::make('address')->label('العنوان التفصيلي'),
                    Forms\Components\TextInput::make('phone')->label('رقم الهاتف'),
                    Forms\Components\TextInput::make('email')->label('البريد الإلكتروني')->email(),
                    Forms\Components\Radio::make('active')->options([
                        ProductActiveEnum::PENDING->value => ProductActiveEnum::PENDING->getLabel(),
                        ProductActiveEnum::ACTIVE->value => ProductActiveEnum::ACTIVE->getLabel(),
                        ProductActiveEnum::BLOCK->value => ProductActiveEnum::BLOCK->getLabel(),
                    ])->label('حالة الخدمة')->default(ProductActiveEnum::PENDING->value),

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->service())
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('رقم المنتج')->searchable(),
                Tables\Columns\TextColumn::make('name')->label('اسم المنتج')->description(fn($record) => $record->expert),
                Tables\Columns\TextColumn::make('category.name')->label('القسم الرئيسي'),
                Tables\Columns\TextColumn::make('city.name')->label('المدينة'),
                Tables\Columns\TextColumn::make('user.name')->label('المتجر')->url(fn($record) => UserResource::getUrl('edit', ['record' => $record->user_id])),
                Tables\Columns\TextColumn::make('views_count')->label('عدد المشاهدات'),
                Tables\Columns\TextColumn::make('created_at')->since()->label('أضيف منذ'),
                Tables\Columns\TextColumn::make('active')->formatStateUsing(fn($state)=>ProductActiveEnum::tryFrom($state)?->getLabel())->color(fn($state)=>ProductActiveEnum::tryFrom($state)?->getColor())->icon(fn($state)=>ProductActiveEnum::tryFrom($state)?->getIcon())->label('الحالة'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
