<?php

namespace App\Filament\Resources;

use App\Enums\IsActiveEnum;
use App\Enums\PlansTypeEnum;
use App\Filament\Resources\PlanResource\Pages;
use App\Filament\Resources\PlanResource\RelationManagers;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Nuwave\Lighthouse\Federation\Types\FieldSet;


class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 24;
    protected static ?string $navigationGroup='الأساسي';
    protected static ?string $label='خطة';
    protected static ?string $modelLabel='خطة';
    protected static ?string $navigationLabel='الخطط';
    protected static ?string $pluralLabel='الخطط';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الخطط')->schema([
                    Forms\Components\TextInput::make('name')->required()->unique(ignoreRecord: true)->label('اسم الخطة'),
                    Forms\Components\Select::make('type')->options([
                        PlansTypeEnum::FREE->value => PlansTypeEnum::FREE->getLabel(),
                        PlansTypeEnum::MONTH->value => PlansTypeEnum::MONTH->getLabel(),
                    ])->label('نوع الخطة')->required(),
                    Forms\Components\RichEditor::make('info')->label('وصف الخطة'),
                    Forms\Components\Toggle::make('is_active')->label('الحالة'),

                    Forms\Components\Fieldset::make('الاسعار')->schema([
                        Forms\Components\TextInput::make('price')->required()->label('سعر الإشتراك')->default(0)->numeric(),
                        Forms\Components\Toggle::make('is_discount')->label('حالة العرض')->live(),
                        Forms\Components\TextInput::make('discount')->required()->label('سعر العرض')->default(0)->numeric()->visible(fn($get) => $get('is_discount')),
                    ])->columns(1),
                    Forms\Components\Toggle::make('is_count')->label('يحتوي إضافات')->reactive()->default(false),
                    Forms\Components\Section::make('الأعداد المتاحة للخطة')->schema([
                        Forms\Components\TextInput::make('options.ads')->numeric()->default(0)->required()->label('عدد الإعلانات'),
                        Forms\Components\TextInput::make('options.slider')->numeric()->default(0)->required()->label('عدد الإعلانات في السلايدر'),
                        Forms\Components\TextInput::make('options.special')->numeric()->default(0)->required()->label('عدد المنتجات المميزة'),
                    ])->visible(fn($get) => $get('is_count')),


                    Forms\Components\Fieldset::make('ميزات الخطة')->schema([
                        Forms\Components\Repeater::make('items')->schema([
                            Forms\Components\Toggle::make('active')->label('حالة التوفر'),
                            Forms\Components\TextInput::make('item')->label('ميزة الخطة'),
                        ])->label('مميزات الخطة')
                    ])


                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('اسم الخطة'),
                Tables\Columns\TextColumn::make('type')->formatStateUsing(fn($state)=>PlansTypeEnum::tryFrom($state)?->getLabel())->color(fn($state)=>PlansTypeEnum::tryFrom($state)?->getColor())->icon(fn($state)=>PlansTypeEnum::tryFrom($state)?->getIcon())->label('نوع الخطة'),
                Tables\Columns\TextColumn::make('is_active')->formatStateUsing(fn($state)=>IsActiveEnum::tryFrom($state)?->getLabel())->color(fn($state)=>IsActiveEnum::tryFrom($state)?->getColor())->icon(fn($state)=>IsActiveEnum::tryFrom($state)?->getIcon())->label('حالة الخطة'),

                Tables\Columns\TextColumn::make('price')->label('سعر الإشتراك'),
                Tables\Columns\TextColumn::make('is_discount')->formatStateUsing(fn($state)=>IsActiveEnum::tryFrom($state)?->getLabel())->color(fn($state)=>IsActiveEnum::tryFrom($state)?->getColor())->icon(fn($state)=>IsActiveEnum::tryFrom($state)?->getIcon())->label('حالة العرض'),

                Tables\Columns\TextColumn::make('discount')->label('سعر العرض'),

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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
