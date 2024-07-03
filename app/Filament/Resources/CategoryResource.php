<?php

namespace App\Filament\Resources;

use App\Enums\CategoryTypeEnum;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Helpers\HelperMedia;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'قسم';
    protected static ?string $modelLabel = 'قسم';
    protected static ?string $navigationLabel = 'الأقسام';
    protected static ?string $pluralLabel = 'الأقسام';
    protected static ?int $navigationSort = 26;
    protected static ?string $navigationGroup = 'الأساسي';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الأقسام والتصنيفات')->schema([
                    HelperMedia::getFileUpload(),
                    Forms\Components\TextInput::make('name')->label('اسم القسم'),
                    Forms\Components\Toggle::make('is_main')->label('قسم رئيسي')->live(),
                    Forms\Components\Select::make('parents')->relationship('parents', 'name')->preload()->multiple()->label('ضمن الأقسام')->visible(fn($get) => !$get('is_main')),
                    Forms\Components\Select::make('type')->options([
                        CategoryTypeEnum::PRODUCT->value => CategoryTypeEnum::PRODUCT->getLabel(),
                        CategoryTypeEnum::JOB->value => CategoryTypeEnum::JOB->getLabel(),
                        CategoryTypeEnum::SEARCH_JOB->value => CategoryTypeEnum::SEARCH_JOB->getLabel(),
                        CategoryTypeEnum::NEWS->value => CategoryTypeEnum::NEWS->getLabel(),
                        CategoryTypeEnum::TENDER->value => CategoryTypeEnum::TENDER->getLabel(),
                    ])->label('نوع القسم')->visible(fn($get) => $get('is_main')),

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                HelperMedia::getImageColumn(),
                Tables\Columns\TextColumn::make('name')->label('اسم القسم'),

                Tables\Columns\TextColumn::make('type')->formatStateUsing(fn($state)=>CategoryTypeEnum::tryFrom($state)?->getLabel())->color(fn($state)=>CategoryTypeEnum::tryFrom($state)?->getColor())->label('اسم القسم'),
            ])->reorderable('sortable')
            ->filters([
                Tables\Filters\SelectFilter::make('parents')->relationship('parents','name')->label('القسم الرئيسي')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //  RelationManagers\ParentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
