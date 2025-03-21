<?php

namespace App\Filament\Resources;

use App\Enums\LevelSellerEnum;
use App\Enums\LevelUserEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Helpers\HelpersEnum;
use App\Models\City;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'مستخدم';
    protected static ?string $modelLabel = 'مستخدم';
    protected static ?string $navigationLabel = 'المستخدمين';
    protected static ?string $pluralLabel = 'المستخدمين';
    protected static ?int $navigationSort = -13;
    protected static ?string $navigationGroup = 'المستخدمين';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المستخدمين')->schema([
                    Forms\Components\Fieldset::make('بيانات المستخدم')->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->conversion('webp')->imageCropAspectRatio('1:1')->imageEditor()->columnSpan(2),
                        Forms\Components\TextInput::make('name')->required()->label('الاسم'),
                        Forms\Components\TextInput::make('email')->required()->email()->unique(ignoreRecord: true)->label('البريد الإلكتروني'),
                        Forms\Components\TextInput::make('password')->required(fn($context) => $context === 'create')
                            ->dehydrateStateUsing(fn($state) => \Hash::make($state))->dehydrated(fn($state) => filled($state))->password()
                            ->same('passwordConfirmation')
                            ->label('كلمة المرور'),
                        Forms\Components\TextInput::make('passwordConfirmation')->required(fn($context) => $context === 'create')
                            ->dehydrated(false)->password()
                            ->label('تأكيد كلمة المرور'),
                        Forms\Components\TextInput::make('phone')->label('رقم الهاتف'),

//                        Forms\Components\DatePicker::make('upgrade_date')/*->required(fn($get) => $get('plan') != null)*/ ->label('تاريخ آخر ترقية'),
                        Forms\Components\DatePicker::make('email_verified_at')->label('حدد تاريخ لتأكيد الحساب'),
                        Forms\Components\Select::make('city_id')->options(City::pluck('name', 'id'))->label('المدينة'),
                        Forms\Components\Select::make('level')->options([
                            LevelUserEnum::ADMIN->value => LevelUserEnum::ADMIN->getLabel(),
                            LevelUserEnum::SELLER->value => LevelUserEnum::SELLER->getLabel(),
                            LevelUserEnum::USER->value => LevelUserEnum::USER->getLabel(),
                            LevelUserEnum::RESTAURANT->value => LevelUserEnum::RESTAURANT->getLabel(),
                        ]),
                        Forms\Components\Toggle::make('is_active')->label('حالة المستخدم'),
                    ]),
                    Forms\Components\Fieldset::make('بيانات المتجر')->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('logo')->collection('logo')->conversion('webp')->label('لوغو المتجر')->columnSpan(2),
                        Forms\Components\TextInput::make('seller_name')->label('اسم المتجر'),
                        Forms\Components\TextInput::make('address')->label('عنوان المتجر'),
                        Forms\Components\Select::make('level_seller')->options([
                            LevelSellerEnum::GOLD->value => LevelSellerEnum::GOLD->getLabel(),
                            LevelSellerEnum::PLATINUM->value => LevelSellerEnum::PLATINUM->getLabel(),
                            LevelSellerEnum::SILVER->value => LevelSellerEnum::SILVER->getLabel(),
                            LevelSellerEnum::BRONZE->value => LevelSellerEnum::BRONZE->getLabel(),
                        ])->label('نوع الإشتراك'),
                        Forms\Components\Textarea::make('info')->label('وصف مختصر')->columnSpan(2),

                       Forms\Components\Grid::make(3)->schema([
                           Forms\Components\Toggle::make('is_default_active')->label('تفعيل المنتجات تلقائيا'),
                           Forms\Components\Toggle::make('is_delivery')->label('خدمة التوصيل'),
                           Forms\Components\Toggle::make('is_seller')->label('تفعيل المتجر'),
                       ]),
                        Forms\Components\TimePicker::make('open_time')->label('يفتح من الساعة'),
                        Forms\Components\TimePicker::make('close_time')->label('يغلق في الساعة')
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->latest())
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image')->conversion('webp')->label('صورة المستخدم')->circular()->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('level')->formatStateUsing(fn($state) => LevelUserEnum::tryFrom($state)->getLabel())->icon(fn($state) => LevelUserEnum::tryFrom($state)->getIcon())->color(fn($state) => LevelUserEnum::tryFrom($state)->getColor())->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('name')->label('الاسم')->toggleable(isToggledHiddenByDefault: false)->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('رقم الهاتف')->toggleable(isToggledHiddenByDefault: false)->searchable(),
                Tables\Columns\TextColumn::make('email')->label('البريد الإلكتروني')->toggleable(isToggledHiddenByDefault: false)->searchable(),
                Tables\Columns\TextColumn::make('seller_name')->label('اسم المتجر')->toggleable(isToggledHiddenByDefault: false)->searchable(),
                Tables\Columns\TextColumn::make('products_count')->label('عدد المنتجات')->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('email_verified_at')->label('حالة التأكيد')->formatStateUsing(fn($state) => HelpersEnum::getEmailVerified($state))
                    ->icon(fn($state) => HelpersEnum::getEmailVerified($state, 'icon'))
                    ->color(fn($state) => HelpersEnum::getEmailVerified($state, 'color'))
                    ->toggleable(isToggledHiddenByDefault: true)->searchable(),

                Tables\Columns\TextColumn::make('level_seller')->label('نوع الإشتراك')
                    ->formatStateUsing(fn($state) => LevelSellerEnum::tryFrom($state)->getLabel())
                    ->color(fn($state) => LevelSellerEnum::tryFrom($state)->getColor())
                    ->toggleable(isToggledHiddenByDefault: true)->searchable(),
                Tables\Columns\TextColumn::make('created_at')->date('Y-m-d')->label('تاريخ التسجيل')->toggleable(isToggledHiddenByDefault: false)->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
