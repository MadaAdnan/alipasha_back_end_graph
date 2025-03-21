<?php

namespace App\Filament\Resources;

use App\Enums\CategoryTypeEnum;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Helpers\HelperMedia;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'منتج';
    protected static ?string $modelLabel = 'منتج';
    protected static ?string $navigationLabel = 'المنتجات';
    protected static ?string $pluralLabel = 'المنتجات';
    protected static ?int $navigationSort = -15;
    protected static ?string $navigationGroup = 'العروض';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المنتجات')->schema([
                    Forms\Components\Select::make('user_id')->options(User::seller()->pluck('users.name', 'users.id'))->label('المتجر')->live()->afterStateUpdated(fn($set, $state) => $set('city_id', User::find($state)?->city_id)),
                    Forms\Components\Select::make('city_id')->options(City::pluck('name', 'id'))->searchable()->label('المدينة'),
                    HelperMedia::getFileUpload(label: 'الصورة الرئيسية', collection: 'main', is_multible: false, ratio: ['1:1']),
                    HelperMedia::getFileUpload(label: 'صور إضافية', name: 'images', collection: 'images', is_multible: true),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('film')->collection('video')->label('فيديو قصير')->acceptedFileTypes(['video/quicktime', 'video/x-ms-wmv', 'video/x-msvideo', 'video/mp4']),
                    Forms\Components\TextInput::make('name')->label('اسم المنتج'),
                    Forms\Components\RichEditor::make('info')->label('وصف المنتج'),
                    Forms\Components\TagsInput::make('tags')->suggestions(fn() => Product::product()->pluck('tags')->flatten()->unique())->label('تاغات'),
                    Forms\Components\Fieldset::make('الأسعار والتوفر')->schema([
                        Forms\Components\Toggle::make('is_available')->label('التوفر في المخزون'),
                        Forms\Components\TextInput::make('price')->label('السعر')->numeric()->required(),
                        Forms\Components\Toggle::make('is_discount')->label('تفعيل العرض')->live(),
                        Forms\Components\TextInput::make('discount')->label('سعر العرض')->numeric()->required(fn($get) => $get('is_discount')),

                    ])->columns(1),
                    Forms\Components\Fieldset::make('التصنيف')->schema([
                        Forms\Components\Select::make('category_id')->options(Category::product()->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live()->required(),
                        Forms\Components\Select::make('sub1_id')->options(fn($get) => Category::find($get('category_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                        Forms\Components\Select::make('sub2_id')->options(fn($get) => Category::find($get('sub1_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->reactive(),
                        Forms\Components\Select::make('sub3_id')->options(fn($get) => Category::find($get('sub2_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                        Forms\Components\Select::make('sub4_id')->options(fn($get) => Category::find($get('sub3_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                    ]),
                    Forms\Components\Fieldset::make('الخصائص')->schema(function ($get) {
                        $list = [];

                        foreach (Attribute::limited()->where('category_id', $get('sub1_id'))->get() as $item) {
                            $list[] =
                                Forms\Components\Select::make('attribute.' . $item->id)->options(Attribute::where('attributes.attribute_id', $item->id)->pluck('name', 'id'))->label($item->name);

                        }
                        return $list;
                    })->columns(1),
                    Forms\Components\Fieldset::make('الخصائص المتعددة')->schema(function ($get) {
                        $list2 = [];

                        foreach (Attribute::multiple()->where('category_id', $get('sub1_id'))->get(['name', 'id']) as $key => $item) {


                            $list = [Forms\Components\Placeholder::make('place.' . $item->id)->columnSpan(4)->dehydrated(false)->label($item->name)];

                            foreach (Attribute::where('attributes.attribute_id', $item->id)->get() as $attr) {
                                $list[] = Forms\Components\Checkbox::make('options.' . $attr->id)->label($attr->name)->inline();
                            }
                            $list2[] = Forms\Components\Grid::make(4)->schema($list);

                        }
                        return $list2;
                    })->columns(1),

                    Forms\Components\Section::make('الخصائص المدخلة')->schema(function ($get) {
                        $list2 = [];

                        foreach (Attribute::write()->where('category_id', $get('sub1_id'))->get(['name', 'id']) as $key => $item) {
                            $list2[] = Forms\Components\TextInput::make('write.' . $item->id)->label($item->name);

                        }
                        return $list2;
                    })->columns(1),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->product())
            ->columns([
                HelperMedia::getImageColumn(collection: 'main'),
                Tables\Columns\TextColumn::make('id')->label('رقم المنتج')->searchable(),
                Tables\Columns\TextColumn::make('name')->label('اسم المنتج')->description(fn($record) => $record->expert)->searchable(),
                Tables\Columns\TextColumn::make('category.name')->label('القسم الرئيسي')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city.name')->label('المدينة'),
                Tables\Columns\TextColumn::make('user.name')->label('المتجر')->url(fn($record) => UserResource::getUrl('edit', ['record' => $record->user_id]))->searchable(),
                Tables\Columns\TextColumn::make('views_count')->label('عدد المشاهدات'),

                Tables\Columns\TextColumn::make('created_at')->since()->label('أضيف منذ'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')->options(User::seller()->pluck('seller_name', 'id'))->label('المتجر')->searchable(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
