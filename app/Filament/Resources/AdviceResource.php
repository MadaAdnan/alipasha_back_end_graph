<?php

namespace App\Filament\Resources;

use App\Enums\ProductActiveEnum;
use App\Filament\Resources\AdviceResource\Pages;
use App\Filament\Resources\AdviceResource\RelationManagers;
use App\Helpers\HelperMedia;
use App\Models\Advice;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdviceResource extends Resource
{
    protected static ?string $model = Advice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = -10;
    protected static ?string $label = 'الإعلانات';
    protected static ?string $modelLabel = 'إعلان';
    protected static ?string $navigationLabel = 'الإعلانات';
    protected static ?string $pluralLabel = 'الإعلانات';
    protected static ?string $navigationGroup = 'حملات إعلانية';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الإعلانات')->schema([
                    HelperMedia::getFileUpload(),
                    Forms\Components\TextInput::make('name')->label('اسم الإعلان'),
                    Forms\Components\TextInput::make('url')->label('رابط الإعلان'),
                    Forms\Components\Select::make('city_id')->options(City::pluck('name', 'id'))->label('المدينة'),
                    Forms\Components\Radio::make('status')->options([
                        ProductActiveEnum::PENDING->value => ProductActiveEnum::PENDING->getLabel(),
                        ProductActiveEnum::ACTIVE->value => ProductActiveEnum::ACTIVE->getLabel(),
                        ProductActiveEnum::BLOCK->value => ProductActiveEnum::BLOCK->getLabel(),
                    ])->label('حالة الإعلان')->inline()->default(ProductActiveEnum::ACTIVE->value),
                    Forms\Components\Select::make('user_id')->options(User::whereNotNull('seller_name')->pluck('seller_name', 'id'))->label('المتجر')->searchable()->live(),
                    Forms\Components\Select::make('category_id')->options(Category::where('is_main', true)->pluck('name', 'id'))->label('القسم')->live()->afterStateUpdated(fn($set) => $set('sub1_id', null))->searchable(),
                    Forms\Components\Select::make('sub1_id')->options(fn($get) => Category::find($get('category_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->required(fn($get) => $get('category_id') !== null)->searchable(),
                    Forms\Components\DatePicker::make('expired_date')->label('تاريخ الإنتهاء')->required(fn($get)=>$get('user_id')!=null)->minDate(fn($context)=>$context=='create' ?now()->addDay():null),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                HelperMedia::getImageColumn(),

                Tables\Columns\TextColumn::make('name')->label('اسم الإعلان'),
                Tables\Columns\TextColumn::make('url')->formatStateUsing(fn($state) => \Str::substr($state, 0, 30))->url(fn($state) => $state, true)->label('اسم الإعلان'),
                Tables\Columns\TextColumn::make('status')->formatStateUsing(fn($state) => ProductActiveEnum::tryFrom($state)?->getLabel())->color(fn($state) => ProductActiveEnum::tryFrom($state)?->getColor())->icon(fn($state) => ProductActiveEnum::tryFrom($state)?->getIcon())->label('حالة الإعلان'),
                Tables\Columns\TextColumn::make('city.name')->label('المدينة'),
                Tables\Columns\TextColumn::make('sub1.name')->label('القسم'),
              //  Tables\Columns\TextColumn::make('views_count')->label('مرات الظهور'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdvice::route('/'),
            'create' => Pages\CreateAdvice::route('/create'),
            'edit' => Pages\EditAdvice::route('/{record}/edit'),
        ];
    }
}
