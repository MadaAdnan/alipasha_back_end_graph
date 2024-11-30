<?php

namespace App\Filament\Resources;

use App\Enums\CategoryTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
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
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

    // permissions
    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'create',
            'update',
            'delete',

            'restore',
            'force_delete'
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_product'); // TODO: Change the autogenerated stub
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create_product'); // TODO: Change the autogenerated stub
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('delete_product'); // TODO: Change the autogenerated stub
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->can('delete_product'); // TODO: Change the autogenerated stub
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()?->can('view_any_product'); // TODO: Change the autogenerated stub
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view_any_product'); // TODO: Change the autogenerated stub
    }

    public static function canRestoreAny(): bool
    {
        return auth()->user()?->can('restore_product'); // TODO: Change the autogenerated stub
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('update_product'); // TODO: Change the autogenerated stub
    }

    public static function canForceDeleteAny(): bool
    {
        return auth()->user()?->can('force_delete_product'); // TODO: Change the autogenerated stub
    }

    public static function canRestore(Model $record): bool
    {
        return auth()->user()?->can('restore_product'); // TODO: Change the autogenerated stub
    }

    public static function canForceDelete(Model $record): bool
    {
        return auth()->user()?->can('force_delete_product'); // TODO: Change the autogenerated stub
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('المنتجات')->schema([
                    Forms\Components\Select::make('user_id')->options(User::seller()->selectRaw('id,name')->pluck('name', 'id'))->label('المتجر')
                        ->searchable()->live()
                        ->afterStateUpdated(fn($set, $state) => $set('city_id', User::find($state)?->city_id)),
                    Forms\Components\Select::make('city_id')->options(City::selectRaw('id,name')->pluck('name', 'id'))->searchable()->label('المدينة'),
                    HelperMedia::getFileUpload(label: 'الصورة الرئيسية', collection: 'image', is_multible: true, ratio: ['1:1'],isWebp: false),
                    HelperMedia::getFileUpload(label: 'صور إضافية', name: 'images', collection: 'images', is_multible: true,isWebp: false),
//                    Forms\Components\SpatieMediaLibraryFileUpload::make('film')->collection('video')->label('فيديو قصير')->acceptedFileTypes(['video/quicktime', 'video/x-ms-wmv', 'video/x-msvideo', 'video/mp4']),
                    Forms\Components\TextInput::make('video')->label('رابط الفيديو إن وجد'),
                    Forms\Components\TextInput::make('name')->label('اسم المنتج'),
                    Forms\Components\Textarea::make('info')->label('وصف المنتج'),
                    Forms\Components\TagsInput::make('tags')->suggestions(fn() => Product::product()->pluck('tags')->flatten()->unique())->label('تاغات'),
                    Forms\Components\Fieldset::make('الأسعار والتوفر')->schema([
                        Forms\Components\Toggle::make('is_available')->label('التوفر في المخزون'),
                        Forms\Components\TextInput::make('price')->label('السعر')->numeric()->required(),
                        Forms\Components\Toggle::make('is_discount')->label('تفعيل العرض')->live(),
                        Forms\Components\TextInput::make('discount')->label('سعر العرض')->numeric()->required(fn($get) => $get('is_discount')),
                        Forms\Components\Toggle::make('is_delivery')->label('التوصيل'),
                        Forms\Components\TextInput::make('weight')->numeric()->label('الوزن')->required(),

                    ])->columns(1),
                    Forms\Components\Radio::make('active')->options([
                        ProductActiveEnum::PENDING->value => ProductActiveEnum::PENDING->getLabel(),
                        ProductActiveEnum::ACTIVE->value => ProductActiveEnum::ACTIVE->getLabel(),
                        ProductActiveEnum::BLOCK->value => ProductActiveEnum::BLOCK->getLabel(),
                    ])->label('حالة المنتج')->default(ProductActiveEnum::PENDING->value),
                    Forms\Components\Radio::make('level')->options([
                        LevelProductEnum::NEWS->value => LevelProductEnum::NEWS->getLabel(),
                        LevelProductEnum::NORMAL->value => LevelProductEnum::NORMAL->getLabel(),
                        LevelProductEnum::SPECIAL->value => LevelProductEnum::SPECIAL->getLabel(),
                    ])->label('رتبة المنتج')->default(LevelProductEnum::NORMAL->value),
                    Forms\Components\Fieldset::make('التصنيف')->schema([
                        Forms\Components\Select::make('category_id')->options(Category::product()->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live()->required(),
                        Forms\Components\Select::make('sub1_id')->options(fn($get) => Category::find($get('category_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                        Forms\Components\Select::make('sub2_id')->options(fn($get) => Category::find($get('sub1_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->reactive(),
                        Forms\Components\Select::make('sub3_id')->options(fn($get) => Category::find($get('sub2_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                        Forms\Components\Select::make('sub4_id')->options(fn($get) => Category::find($get('sub3_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                    ]),
                    Forms\Components\Select::make('colors')->relationship('colors', 'name')->preload()->label('الألوان')->visible(fn($get) => Category::find($get('category_id'))?->has_color)->multiple()->searchable(),

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
            ->columns([

                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image')->label('الصورة')->size(50),
                Tables\Columns\TextColumn::make('id')->label('رقم المنتج')->searchable(),
                Tables\Columns\TextInputColumn::make('weight')->label('وزن المنتج')->searchable(),
                Tables\Columns\ToggleColumn::make('is_delivery')->label('قابل للتوصيل')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->label('اسم القسم')->description(fn($record) => $record->sub1?->name),
                Tables\Columns\TextColumn::make('name')->label('اسم المنتج')->description(fn($record) => $record->expert)->searchable(),
                Tables\Columns\TextColumn::make('city.name')->label('المدينة'),
                Tables\Columns\TextColumn::make('user.name')->label('المتجر')->url(fn($record) => UserResource::getUrl('edit', ['record' => $record->user_id]))->searchable(),
                Tables\Columns\TextColumn::make('views_count')->label('عدد المشاهدات'),
                Tables\Columns\TextColumn::make('active')->formatStateUsing(fn($state) => ProductActiveEnum::tryFrom($state)?->getLabel())->color(fn($state) => ProductActiveEnum::tryFrom($state)?->getColor())->icon(fn($state) => ProductActiveEnum::tryFrom($state)?->getIcon())->label('الحالة'),
                Tables\Columns\TextColumn::make('level')->formatStateUsing(fn($state) => LevelProductEnum::tryFrom($state)?->getLabel())->color(fn($state) => LevelProductEnum::tryFrom($state)?->getColor())->icon(fn($state) => LevelProductEnum::tryFrom($state)?->getIcon())->label('تمييز المنتج'),
                Tables\Columns\TextColumn::make('created_at')->since()->label('أضيف منذ'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')->options(User::seller()->pluck('seller_name', 'id'))->label('المتجر')->searchable(),
                Tables\Filters\Filter::make('level')->form([
                    Forms\Components\Select::make('level')->options([
                        LevelProductEnum::NORMAL->value => LevelProductEnum::NORMAL->getLabel(),
                        LevelProductEnum::SPECIAL->value => LevelProductEnum::SPECIAL->getLabel(),
                    ])->label('نوع المنتج')
                ])->query(fn($query, $data) => $query->when($data['level'] != null, fn($q) => $q->where('level', $data['level']))),
                Tables\Filters\Filter::make('category')->modifyQueryUsing(fn($query) => $query->whereNull('category_id')),
                Tables\Filters\Filter::make('category_filter')->form([
                    Forms\Components\Select::make('category_id')->options(Category::where('categories.is_main', true)->pluck('name', 'id'))->label('القسم الرئيسي')->live(),
                    Forms\Components\Select::make('sub1_id')->options(function ($get) {
                        if ($get('category_id') != null) {
                            return Category::find($get('category_id'))->children->pluck('name', 'id');
                        }
                    })->label('القسم الرئيسي')->live(),
                    Forms\Components\Select::make('sub2_id')->options(function ($get) {
                        if ($get('sub1_id') != null) {
                            return Category::find($get('sub1_id'))->children->pluck('name', 'id');
                        }
                    })->label('القسم الرئيسي')->live(),

                ])->query(function ($query, $data) {
                    $query->when(
                        $data['category_id'],
                        fn(Builder $query, $date): Builder => $query->where('category_id', $date),
                    )
                        ->when(
                            $data['sub1_id'],
                            fn(Builder $query, $date): Builder => $query->where('sub1_id', $date),
                        )
                        ->when(
                            $data['sub2_id'],
                            fn(Builder $query, $date): Builder => $query->where('sub2_id', $date),
                        );
                })
            ])
            ->headerActions([
                Tables\Actions\Action::make('delivery')->form([
                    Forms\Components\Select::make('categories')->options(Category::where('is_main',true)
                        ->where(fn($query)=>$query->where('type','product')->orWhere('type','restaurant'))->pluck('name','id'))
                        ->multiple()->label('الأقسام')->required(),
                    Forms\Components\Toggle::make('is_delivery')->label('حالة التوصيل'),
                ])->action(fn($data)=>Product::whereIn('category_id',$data['categories'])->update([
                    'is_delivery'=>$data['is_delivery']
                ]))->label('حالة التوصيل للأقسام')
            ])
            ->actions([
                Tables\Actions\EditAction::make()->mutateFormDataUsing(function ($data) {
                    $data['expert'] = \Str::words(strip_tags(html_entity_decode($data['info'])), 15);
                    return $data;
                }),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('نقل إلى سلة المحذوفات'),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('category')->form([
                        Forms\Components\Fieldset::make('التصنيف')->schema([
                            Forms\Components\Select::make('category_id')->options(Category::product()->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live()->required(),
                            Forms\Components\Select::make('sub1_id')->options(fn($get) => Category::find($get('category_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                            Forms\Components\Select::make('sub2_id')->options(fn($get) => Category::find($get('sub1_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->reactive(),
                            Forms\Components\Select::make('sub3_id')->options(fn($get) => Category::find($get('sub2_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                            Forms\Components\Select::make('sub4_id')->options(fn($get) => Category::find($get('sub3_id'))?->children?->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live(),
                        ]),
                    ])
                        ->action(function ($data, $records) {
                        $ids = $records->pluck('id')->toArray();
                        Product::whereIn('id', $ids)->update([
                            'category_id' => $data['category_id'],
                            'sub1_id' => $data['sub1_id'],
                            'sub2_id' => $data['sub2_id'],
                            'sub3_id' => $data['sub3_id'],
                        ]);
                        Notification::make('success')->title('نجاح العملية')->body('تم تخصيص الأقسام بنجاح')->success()->send();
                    }),
                    Tables\Actions\BulkAction::make('weight')->label('احسب الوزن')->action(function ($records){
                        foreach ($records as $record){
                         $res=   \Http::post('http://85.215.154.88:5000/calculate-weight',[
                                'input_text'=>$record->name . "  عدد 1 \n"
                            ]);
                            if($res->successful() && (double) $res->json('total_weight')>0){
                               Product::where('id',$record->id)->update([
                                   'weight'=>$res->json('total_weight')
                               ]);
                            }
                        }
                    })
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
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
