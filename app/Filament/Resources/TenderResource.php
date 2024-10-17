<?php

namespace App\Filament\Resources;


use App\Enums\ProductActiveEnum;
use App\Filament\Resources\TenderResource\Pages;
use App\Filament\Resources\TenderResource\RelationManagers;

use App\Models\Category;
use App\Models\City;
use App\Models\Product as Tender;

use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Model;


class TenderResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Tender::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'مناقصة';
    protected static ?string $modelLabel = 'مناقصة';
    protected static ?string $navigationLabel = 'المناقصات';
    protected static ?string $pluralLabel = 'المناقصات';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'العروض';
    protected static ?string $slug = 'tenders';

    // permissions
    public static function getPermissionPrefixes(): array
    {
        return [
          'view_any_tenders',
            'create_tenders',
            'update_tenders',
            'delete_tenders',

            'restore_tenders',
            'force_delete_tenders'
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_tenders'); // TODO: Change the autogenerated stub
    }

    public static function canCreate(): bool
    {
        return  auth()->user()?->can('create_tenders'); // TODO: Change the autogenerated stub
    }

    public static function canDelete(Model $record): bool
    {
        return  auth()->user()?->can('delete_tenders'); // TODO: Change the autogenerated stub
    }

    public static function canDeleteAny(): bool
    {
        return  auth()->user()?->can('delete_tenders'); // TODO: Change the autogenerated stub
    }

    public static function canView(Model $record): bool
    {
        return  auth()->user()?->can('view_any_tenders'); // TODO: Change the autogenerated stub
    }

    public static function canViewAny(): bool
    {
        return  auth()->user()?->can('view_any_tenders'); // TODO: Change the autogenerated stub
    }

    public static function canRestoreAny(): bool
    {
        return  auth()->user()?->can('restore_tenders'); // TODO: Change the autogenerated stub
    }

    public static function canEdit(Model $record): bool
    {
        return  auth()->user()?->can('update_tenders'); // TODO: Change the autogenerated stub
    }

    public static function canForceDeleteAny(): bool
    {
        return  auth()->user()?->can('force_delete_tenders'); // TODO: Change the autogenerated stub
    }

    public static function canRestore(Model $record): bool
    {
        return  auth()->user()?->can('restore_tenders'); // TODO: Change the autogenerated stub
    }

    public static function canForceDelete(Model $record): bool
    {
        return  auth()->user()?->can('force_delete_tenders'); // TODO: Change the autogenerated stub
    }





    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المناقصات')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('docs')->collection('docs')->acceptedFileTypes(['application/pdf', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])->label('ملف مرفقات')->multiple()->maxFiles(3)->downloadable()->openable(),
                    Forms\Components\Select::make('user_id')->options(User::seller()->pluck('users.seller_name', 'users.id'))->label('المتجر')->live()->afterStateUpdated(fn($set, $state) => $set('city_id', User::find($state)?->city_id)),
                    Forms\Components\Select::make('city_id')->options(City::pluck('name', 'id'))->searchable()->label('المدينة'),
                    Forms\Components\TextInput::make('name')->label('اسم المناقصة'),
                    Forms\Components\Textarea::make('info')->label('وصف المناقصة'),
                    Forms\Components\TagsInput::make('tags')->suggestions(fn() => Product::tender()->pluck('tags')->flatten()->unique())->label('تاغات'),
                    Forms\Components\Fieldset::make('بيانات المناقصة')->schema([
                        Forms\Components\DatePicker::make('start_date')->label('تاريخ بداية التقديم'),
                        Forms\Components\DatePicker::make('end_date')->label('تاريخ نهاية التقديم'),
                        Forms\Components\TextInput::make('code')->label('كود المناقصة'),
                        Forms\Components\TextInput::make('url')->label('رابط التقديم')->url(),
                        Forms\Components\TextInput::make('email')->label('البريد الإلكتروني')->email(),
                        Forms\Components\TextInput::make('phone')->label('رقم الهاتف')->reactive(),
                    ]),
                    Forms\Components\Radio::make('active')->options([
                        ProductActiveEnum::PENDING->value => ProductActiveEnum::PENDING->getLabel(),
                        ProductActiveEnum::ACTIVE->value => ProductActiveEnum::ACTIVE->getLabel(),
                        ProductActiveEnum::BLOCK->value => ProductActiveEnum::BLOCK->getLabel(),
                    ])->label('حالة المناقصة')->default(ProductActiveEnum::PENDING->value),
                    Forms\Components\Fieldset::make('التصنيف')->schema([
                        Forms\Components\Select::make('category_id')->options(Category::tender()->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live()->required(),
                        Forms\Components\Select::make('sub1_id')->options(fn($get) => Category::find($get('category_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                        Forms\Components\Select::make('sub2_id')->options(fn($get) => Category::find($get('sub1_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->reactive(),
                        Forms\Components\Select::make('sub3_id')->options(fn($get) => Category::find($get('sub2_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                        Forms\Components\Select::make('sub4_id')->options(fn($get) => Category::find($get('sub3_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                    ]),


                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->tender())
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('رقم المنتج')->searchable(),
                Tables\Columns\TextColumn::make('name')->label('اسم المنتج')->description(fn($record) => $record->expert),
                Tables\Columns\TextColumn::make('category.name')->label('القسم الرئيسي'),
                Tables\Columns\TextColumn::make('user.name')->label('المتجر')->url(fn($record) => UserResource::getUrl('edit', ['record' => $record->user_id])),
                Tables\Columns\TextColumn::make('end_date')->label('نهاية التقديم'),
                Tables\Columns\TextColumn::make('views_count')->label('عدد المشاهدات'),
                Tables\Columns\TextColumn::make('created_at')->since()->label('أضيف منذ'),
                Tables\Columns\TextColumn::make('active')->formatStateUsing(fn($state)=>ProductActiveEnum::tryFrom($state)?->getLabel())->color(fn($state)=>ProductActiveEnum::tryFrom($state)?->getColor())->icon(fn($state)=>ProductActiveEnum::tryFrom($state)?->getIcon())->label('الحالة'),

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
            'index' => Pages\ListTenders::route('/'),
            'create' => Pages\CreateTender::route('/create'),
            'edit' => Pages\EditTender::route('/{record}/edit'),
        ];
    }
}
