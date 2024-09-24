<?php

namespace App\Filament\Resources;

use App\Enums\ProductActiveEnum;
use App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\NewsResource\RelationManagers;
use App\Helpers\HelperMedia;
use App\Models\Category;
use App\Models\City;
use App\Models\News;
use App\Models\Product;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NewsResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'خبر';
    protected static ?string $modelLabel = 'خبر';
    protected static ?string $navigationLabel = 'الأخبار';
    protected static ?string $pluralLabel = 'الأخبار';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'العروض';
    protected static ?string $slug = 'news';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الأخبار')->schema([
                    HelperMedia::getFileUpload(),
                    Forms\Components\Select::make('user_id')->options(User::seller()->pluck('users.seller_name', 'users.id'))->label('الناشر')->live()->afterStateUpdated(fn($set, $state) => $set('city_id', User::find($state)?->city_id)),
                    Forms\Components\Select::make('city_id')->options(City::pluck('name', 'id'))->searchable()->label('المدينة'),
                    Forms\Components\TextInput::make('name')->label('عنوان الخبر'),
                    Forms\Components\Textarea::make('info')->label('الخبر'),
                    Forms\Components\TagsInput::make('tags')->suggestions(fn() => Product::news()->pluck('tags')->flatten()->unique())->label('تاغات'),

                    Forms\Components\Fieldset::make('التصنيف')->schema([
                        Forms\Components\Select::make('category_id')->options(Category::news()->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live()->required(),
                        Forms\Components\Select::make('sub1_id')->options(fn($get) => Category::find($get('category_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                        Forms\Components\Select::make('sub2_id')->options(fn($get) => Category::find($get('sub1_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->reactive(),
                        Forms\Components\Select::make('sub3_id')->options(fn($get) => Category::find($get('sub2_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                        Forms\Components\Select::make('sub4_id')->options(fn($get) => Category::find($get('sub3_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                    ]),
                    Forms\Components\Radio::make('active')->options([
                        ProductActiveEnum::PENDING->value => ProductActiveEnum::PENDING->getLabel(),
                        ProductActiveEnum::ACTIVE->value => ProductActiveEnum::ACTIVE->getLabel(),
                        ProductActiveEnum::BLOCK->value => ProductActiveEnum::BLOCK->getLabel(),
                    ])->label('حالة الخبر')->default(ProductActiveEnum::PENDING->value),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->news())
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('رقم الخبر')->searchable(),
                Tables\Columns\TextColumn::make('name')->label('عنوان  الخبر')->description(fn($record) => $record->expert),
                Tables\Columns\TextColumn::make('category.name')->label('القسم الرئيسي'),
                Tables\Columns\TextColumn::make('user.name')->label('الناشر')->url(fn($record) => UserResource::getUrl('edit', ['record' => $record->user_id])),
                Tables\Columns\TextColumn::make('views_count')->label('عدد المشاهدات'),
                Tables\Columns\TextColumn::make('created_at')->since()->label('أضيف منذ'),
                Tables\Columns\TextColumn::make('active')->formatStateUsing(fn($state) => ProductActiveEnum::tryFrom($state)?->getLabel())->color(fn($state) => ProductActiveEnum::tryFrom($state)?->getColor())->icon(fn($state) => ProductActiveEnum::tryFrom($state)?->getIcon())->label('الحالة'),

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
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
