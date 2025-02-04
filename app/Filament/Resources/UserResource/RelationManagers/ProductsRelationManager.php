<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Filament\Imports\ProductImporter;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\UserResource;
use App\Helpers\HelperMedia;
use App\Imports\ProductsImporter;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use App\Models\User;
use Excel;
use Filament\Actions\ImportAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;


class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';


    protected function canDeleteAny(): bool
    {
        return auth()->user()->can('delete_product'); // TODO: Change the autogenerated stub
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المنتجات')->schema([
                    Forms\Components\Select::make('user_id')->options(User::seller()->selectRaw('id,name')->pluck('name', 'id'))->label('المتجر')
                        ->searchable()->live()
                        ->afterStateUpdated(fn($set, $state) => $set('city_id', User::find($state)?->city_id)),
                    Forms\Components\Select::make('city_id')->options(City::selectRaw('id,name')->pluck('name', 'id'))->searchable()->label('المدينة'),
                    HelperMedia::getFileUpload(label: 'الصورة الرئيسية', collection: 'image', is_multible: true, ratio: ['1:1'],),
                    HelperMedia::getFileUpload(label: 'صور إضافية', name: 'images', collection: 'images', is_multible: true),
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([

                Tables\Columns\TextColumn::make('id')->label('#'),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image')->label('الصورة')->size(50),
                Tables\Columns\TextColumn::make('id')->label('رقم المنتج')->searchable()->url(fn($record)=>ProductResource::getUrl('edit',['record'=>$record->id]))->openUrlInNewTab(true),
                Tables\Columns\TextInputColumn::make('weight')->label('وزن المنتج')->searchable(),
                Tables\Columns\ToggleColumn::make('is_delivery')->label('قابل للتوصيل')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->label('اسم القسم')->description(fn($record) => $record->sub1?->name),
                Tables\Columns\TextColumn::make('name')->label('اسم المنتج')->description(fn($record) => $record->expert)->searchable(),
                Tables\Columns\TextColumn::make('price')->label('سعر المنتج'),
                Tables\Columns\TextColumn::make('city.name')->label('المدينة'),
                Tables\Columns\TextColumn::make('user.name')->label('المتجر')->url(fn($record) => UserResource::getUrl('edit', ['record' => $record->user_id]))->searchable(),
                Tables\Columns\TextColumn::make('views_count')->label('عدد المشاهدات'),
                Tables\Columns\TextColumn::make('active')->formatStateUsing(fn($state) => ProductActiveEnum::tryFrom($state)?->getLabel())->color(fn($state) => ProductActiveEnum::tryFrom($state)?->getColor())->icon(fn($state) => ProductActiveEnum::tryFrom($state)?->getIcon())->label('الحالة'),
                Tables\Columns\TextColumn::make('level')->formatStateUsing(fn($state) => LevelProductEnum::tryFrom($state)?->getLabel())->color(fn($state) => LevelProductEnum::tryFrom($state)?->getColor())->icon(fn($state) => LevelProductEnum::tryFrom($state)?->getIcon())->label('تمييز المنتج'),
                Tables\Columns\TextColumn::make('created_at')->since()->label('أضيف منذ'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                ExportAction::make()->exports([
                    ExcelExport::make()->fromTable()->withChunkSize(200)->askForFilename()
                        ->withFilename(fn ($filename) => 'ali-pasha-products' . $filename),
                ]),
Tables\Actions\Action::make('import')->form([
    Forms\Components\FileUpload::make('file')->label('رفع ملف')->storeFiles()
])->action(function($data){

    if ($data['file'] instanceof TemporaryUploadedFile) {
        static::import($data['file']);
    } else {
        throw new \Exception("الملف غير صحيح، تأكد من رفعه بشكل صحيح.");
    }
})
                /* Tables\Actions\EditAction::make()->mutateFormDataUsing(function ($data) {
                    $data['expert'] = \Str::words(strip_tags(html_entity_decode($data['info'])), 15);
                    return $data;
                }),*/

            ])
            ->actions([
//                Tables\Actions\EditAction::make()->openUrlInNewTab(true),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('category')->form([
                        Forms\Components\Fieldset::make('التصنيف')->schema([
                            Forms\Components\Select::make('category_id')->options(Category::product()->pluck('name', 'id')->toArray())->label('يتبع القسم')->searchable()->live()->required(),
                            Forms\Components\Select::make('sub1_id')->options(fn($get) => Category::find($get('category_id'))?->children?->pluck('name', 'id')->toArray())->label('يتبع القسم')->searchable()->live(),
                            Forms\Components\Select::make('sub2_id')->options(fn($get) => Category::find($get('sub1_id'))?->children?->pluck('name', 'id')->toArray())->label('يتبع القسم')->searchable()->reactive(),
                            Forms\Components\Select::make('sub3_id')->options(fn($get) => Category::find($get('sub2_id'))?->children?->pluck('name', 'id')->toArray())->label('يتبع القسم')->searchable()->live(),
                            Forms\Components\Select::make('sub4_id')->options(fn($get) => Category::find($get('sub3_id'))?->children?->pluck('name', 'id')->toArray())->label('يتبع القسم')->searchable()->live(),
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
                ]),
            ]);
    }

    public static function import(TemporaryUploadedFile  $file)
    {
        dd($file);
        $filePath= ($file);
        Excel::import(new ProductsImporter, $filePath);
    }
}
