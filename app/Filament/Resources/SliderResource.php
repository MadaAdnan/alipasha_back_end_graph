<?php

namespace App\Filament\Resources;

use App\Enums\ProductActiveEnum;
use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Helpers\HelperMedia;
use App\Models\Category;
use App\Models\City;
use App\Models\Slider;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 16;
    protected static ?string $label = 'السلايدر';
    protected static ?string $modelLabel = 'السلايدر';
    protected static ?string $navigationLabel = 'السلايدر';
    protected static ?string $pluralLabel = 'السلايدر';
    protected static ?string $navigationGroup = 'حملات إعلانية';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الإعلانات')->schema([
                    HelperMedia::getFileUpload(),
                    Forms\Components\TextInput::make('url')->label('رابط الإعلان'),
                    Forms\Components\Select::make('city_id')->options(City::pluck('name', 'id'))->label('المدينة'),
                    Forms\Components\Radio::make('status')->options([
                        ProductActiveEnum::PENDING->value => ProductActiveEnum::PENDING->getLabel(),
                        ProductActiveEnum::ACTIVE->value => ProductActiveEnum::ACTIVE->getLabel(),
                        ProductActiveEnum::BLOCK->value => ProductActiveEnum::BLOCK->getLabel(),
                    ])->label('حالة الإعلان')->inline()->default(ProductActiveEnum::ACTIVE->value),
                    Forms\Components\Select::make('user_id')->options(User::whereNotNull('seller_name')->pluck('name', 'id'))->label('المتجر')->searchable(),
                    Forms\Components\Select::make('category_id')->options(Category::where('is_main', true)->pluck('name', 'id'))->label('القسم')->live()->afterStateUpdated(fn($set) => $set('sub1_id', null))->searchable(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                HelperMedia::getImageColumn(),
                Tables\Columns\TextColumn::make('url')->formatStateUsing(fn($state) => \Str::substr($state,0,30))->url(fn($state) => $state, true)->label('اسم الإعلان'),
                Tables\Columns\TextColumn::make('status')->formatStateUsing(fn($state) =>ProductActiveEnum::tryFrom($state)?->getLabel() )->color(fn($state) =>ProductActiveEnum::tryFrom($state)?->getColor() )->icon(fn($state) =>ProductActiveEnum::tryFrom($state)?->getIcon() )->label('حالة الإعلان'),
                Tables\Columns\TextColumn::make('city.name')->label('المدينة'),
                Tables\Columns\TextColumn::make('category.name')->label('القسم'),
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
