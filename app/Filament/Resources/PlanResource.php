<?php

namespace App\Filament\Resources;

use App\Enums\IsActiveEnum;
use App\Enums\PlansDurationEnum;
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
    protected static ?string $navigationGroup = 'الأساسي';
    protected static ?string $label = 'خطة';
    protected static ?string $modelLabel = 'خطة';
    protected static ?string $navigationLabel = 'الخطط';
    protected static ?string $pluralLabel = 'الخطط';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الخطط')->schema([
                    Forms\Components\TextInput::make('name')->required()->unique(ignoreRecord: true)->label('اسم الخطة'),
                    Forms\Components\Select::make('type')->options([
                        PlansTypeEnum::PRESENT->value => PlansTypeEnum::PRESENT->getLabel(),
                        PlansTypeEnum::SERVICE->value => PlansTypeEnum::SERVICE->getLabel(),
                    ])->label('نوع الخطة')->required()->live(),

                    Forms\Components\Select::make('duration')->options(function () {
                        $list = [
                            PlansDurationEnum::MONTH->value => PlansDurationEnum::MONTH->getLabel(),
                            PlansDurationEnum::YEAR->value => PlansDurationEnum::YEAR->getLabel(),
                        ];
                        $plan = Plan::where('duration', 'free')->first();
                        if ($plan == null) {
                            $list[PlansDurationEnum::FREE->value] = PlansDurationEnum::FREE->getLabel();
                        }
                        return $list;
                    })->label('مدة الخطة')->required(),

                    Forms\Components\Fieldset::make('مميزات الخطة')->schema([
                        Forms\Components\TextInput::make('special_count')->numeric()->required()->label('عدد المنتجات المميزة'),
                        Forms\Components\TextInput::make('products_count')->numeric()->required()->label('عدد المنتجات / شهرياً'),
                        Forms\Components\TextInput::make('ads_count')->numeric()->required()->label('عدد الإعلانات'),
                        Forms\Components\Toggle::make('special_store')->label('متجر مميز'),
                    ])->visible(fn($get)=>$get('type')===PlansTypeEnum::PRESENT->value),

                    Forms\Components\Textarea::make('info')->label('وصف الخطة'),
                    Forms\Components\Toggle::make('is_active')->label('الحالة'),

                    Forms\Components\Fieldset::make('الاسعار')->schema([
                        Forms\Components\TextInput::make('price')->required()->label('سعر الإشتراك')->default(0)->numeric(),
                        Forms\Components\Toggle::make('is_discount')->label('حالة العرض')->live(),
                        Forms\Components\TextInput::make('discount')->required()->label('سعر العرض')->default(0)->numeric()->visible(fn($get) => $get('is_discount')),
                    ])->columns(1),


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
            ->reorderable('sortable')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('اسم الخطة'),
                Tables\Columns\TextColumn::make('type')->formatStateUsing(fn($state) => PlansTypeEnum::tryFrom($state)?->getLabel())->color(fn($state) => PlansTypeEnum::tryFrom($state)?->getColor())->icon(fn($state) => PlansTypeEnum::tryFrom($state)?->getIcon())->label('نوع الخطة'),
                Tables\Columns\TextColumn::make('duration')->formatStateUsing(fn($state) => PlansDurationEnum::tryFrom($state)?->getLabel())->color(fn($state) => PlansDurationEnum::tryFrom($state)?->getColor())->icon(fn($state) => PlansDurationEnum::tryFrom($state)?->getIcon())->label('مدة الخطة'),
                Tables\Columns\TextColumn::make('is_active')->formatStateUsing(fn($state) => IsActiveEnum::tryFrom($state)?->getLabel())->color(fn($state) => IsActiveEnum::tryFrom($state)?->getColor())->icon(fn($state) => IsActiveEnum::tryFrom($state)?->getIcon())->label('حالة الخطة'),
                Tables\Columns\TextColumn::make('price')->label('سعر الإشتراك'),
                Tables\Columns\TextColumn::make('is_discount')->formatStateUsing(fn($state) => IsActiveEnum::tryFrom($state)?->getLabel())->color(fn($state) => IsActiveEnum::tryFrom($state)?->getColor())->icon(fn($state) => IsActiveEnum::tryFrom($state)?->getIcon())->label('حالة العرض'),

                Tables\Columns\TextColumn::make('users_count')->label('عدد المشتركين'),
                Tables\Columns\TextColumn::make('discount')->label('سعر العرض'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')->options([
                    PlansTypeEnum::PRESENT->value => PlansTypeEnum::PRESENT->getLabel(),
                    PlansTypeEnum::SERVICE->value => PlansTypeEnum::SERVICE->getLabel(),
                ])->label('نوع الخطة')
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
            RelationManagers\UsersRelationManager::class
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
