<?php

namespace App\Filament\Resources;

use App\Enums\CategoryTypeEnum;
use App\Filament\Resources\TenderResource\Pages;
use App\Filament\Resources\TenderResource\RelationManagers;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use App\Models\Tender;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TenderResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'مناقصة';
    protected static ?string $modelLabel = 'مناقصة';
    protected static ?string $navigationLabel = 'المناقصات';
    protected static ?string $pluralLabel = 'المناقصات';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'العروض';
    protected static ?string $slug = 'tenders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المناقصات')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('docs')->collection('docs')->acceptedFileTypes(['application/pdf', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])->label('ملف مرفقات')->multiple()->maxFiles(3)->downloadable()->openable(),
                    Forms\Components\Select::make('user_id')->options(User::seller()->pluck('users.seller_name', 'users.id'))->label('المتجر')->live()->afterStateUpdated(fn($set, $state) => $set('city_id', User::find($state)?->city_id)),
                    Forms\Components\Select::make('city_id')->options(City::pluck('name', 'id'))->searchable()->label('المدينة'),
                    Forms\Components\TextInput::make('name')->label('اسم المناقصة'),
                    Forms\Components\RichEditor::make('info')->label('وصف المناقصة'),
                    Forms\Components\TagsInput::make('tags')->suggestions(fn() => Product::tender()->pluck('tags')->flatten()->unique())->label('تاغات'),
                    Forms\Components\Fieldset::make('بيانات المناقصة')->schema([
                        Forms\Components\DatePicker::make('start_date')->label('تاريخ بداية التقديم'),
                        Forms\Components\DatePicker::make('end_date')->label('تاريخ نهاية التقديم'),
                        Forms\Components\TextInput::make('code')->label('كود المناقصة'),
                        Forms\Components\TextInput::make('url')->label('رابط التقديم')->url(),
                        Forms\Components\TextInput::make('email')->label('البريد الإلكتروني')->email(),
                        Forms\Components\TextInput::make('phone')->label('رقم الهاتف')->reactive(),
                    ]),
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
            'index' => Pages\ListTenders::route('/'),
            'create' => Pages\CreateTender::route('/create'),
            'edit' => Pages\EditTender::route('/{record}/edit'),
        ];
    }
}
