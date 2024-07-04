<?php

namespace App\Filament\Resources;

use App\Enums\AttributeTypeEnum;
use App\Filament\Resources\AttributeResource\Pages;
use App\Filament\Resources\AttributeResource\RelationManagers;
use App\Models\Attribute;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'سمة';
    protected static ?string $modelLabel = 'سمة';
    protected static ?string $navigationLabel = 'السمات';
    protected static ?string $pluralLabel = 'السمات';
    protected static ?int $navigationSort = 27;
    protected static ?string $navigationGroup = 'الأساسي';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الخصائص')->schema([
                    Forms\Components\TextInput::make('name'),
                    Forms\Components\Radio::make('type')->options([
                        AttributeTypeEnum::LIMIT->value => AttributeTypeEnum::LIMIT->getLabel(),
                        AttributeTypeEnum::MULTIPLE->value => AttributeTypeEnum::MULTIPLE->getLabel(),
                        AttributeTypeEnum::VALUE->value => AttributeTypeEnum::VALUE->getLabel(),
                    ])->default(AttributeTypeEnum::LIMIT->value),
                    Forms\Components\Select::make('category_id')->options(Category::pluck('name', 'id'))->searchable(),
//                    Forms\Components\Select::make('attribute_id')->options(Attribute::whereNull('attributes.attribute_id')->pluck('name', 'id'))->searchable()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->whereNull('attribute_id'))
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('attribute.name'),
                Tables\Columns\TextColumn::make('type')->formatStateUsing(fn($state) => AttributeTypeEnum::tryFrom($state)?->getLabel())->icon(fn($state) => AttributeTypeEnum::tryFrom($state)?->getIcon())->color(fn($state) => AttributeTypeEnum::tryFrom($state)?->getColor())
            ])
            ->filters([
                //
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
            RelationManagers\AttributesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttributes::route('/'),
            'create' => Pages\CreateAttribute::route('/create'),
            'edit' => Pages\EditAttribute::route('/{record}/edit'),
        ];
    }
}
