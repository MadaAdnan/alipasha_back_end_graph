<?php

namespace App\Filament\Seller\Resources;

use App\Enums\CategoryTypeEnum;
use App\Enums\ProductActiveEnum;
use App\Filament\Seller\Resources\JobResource\Pages;
use App\Filament\Seller\Resources\JobResource\RelationManagers;
use App\Models\Attribute;
use App\Models\Job;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $slug = 'jobs';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'وظيفة';
    protected static ?string $modelLabel = 'وظيفة';
    protected static ?string $navigationLabel = 'الوظائف';
    protected static ?string $pluralLabel = 'الوظائف';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'العروض';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الوظائف والشواغر')->schema([
//                    Forms\Components\Select::make('user_id')->options(User::seller()->pluck('users.seller_name', 'users.id'))->label('المتجر')->live()->afterStateUpdated(fn($set, $state) => $set('city_id', User::find($state)?->city_id)),
//                    Forms\Components\Select::make('city_id')->options(City::pluck('name', 'id'))->searchable()->label('المدينة'),
//                    Forms\Components\TextInput::make('name')->label('اسم الوظيفة'),
                    Forms\Components\Textarea::make('info')->label('وصف الوظيفة'),
                    Forms\Components\TagsInput::make('tags')->suggestions(fn() => Product::job()->pluck('tags')->flatten()->unique())->label('تاغات'),
                    Forms\Components\Select::make('type')->options([
                        CategoryTypeEnum::SEARCH_JOB->value => CategoryTypeEnum::SEARCH_JOB->getLabel(),
                        CategoryTypeEnum::JOB->value => CategoryTypeEnum::JOB->getLabel(),
                    ])->label('نوع المنشور')->required()->live(),
                    Forms\Components\Fieldset::make('بيانات الوظيفة')->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\DatePicker::make('start_date')->label('تاريخ بداية التقديم'),
                            Forms\Components\DatePicker::make('end_date')->label('تاريخ نهاية التقديم'),
                            Forms\Components\TextInput::make('code')->label('كود الوظيفة'),
                            Forms\Components\TextInput::make('url')->label('رابط التقديم')->url(),
                        ])->visible(fn($get) => $get('type') === CategoryTypeEnum::JOB->value),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('docs')->collection('docs')->acceptedFileTypes(['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])->label(fn($get) => $get('type') === CategoryTypeEnum::SEARCH_JOB->value ? 'رفع CV' : 'مرفق')
                            ->openable()->deletable()
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('email')->label('البريد الإلكتروني')->email(),
                        Forms\Components\TextInput::make('phone')->label('رقم الهاتف'),

                    ]),
//                    Forms\Components\Radio::make('active')->options([
//                        ProductActiveEnum::PENDING->value => ProductActiveEnum::PENDING->getLabel(),
//                        ProductActiveEnum::ACTIVE->value => ProductActiveEnum::ACTIVE->getLabel(),
//                        ProductActiveEnum::BLOCK->value => ProductActiveEnum::BLOCK->getLabel(),
//                    ])->label('حالة الوظيفة')->default(ProductActiveEnum::PENDING->value),
                    Forms\Components\Fieldset::make('التصنيف')->schema([
                        Forms\Components\Select::make('category_id')->options(Category::job()->pluck('name', 'id'))->label('يتبع القسم')->searchable()->live()->required(),
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
            ->modifyQueryUsing(fn($query) => $query->job()->where('user_id', auth()->id()))
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('رقم المنتج')->searchable(),
                Tables\Columns\TextColumn::make('name')->label('اسم المنتج')->description(fn($record) => $record->expert),
                Tables\Columns\TextColumn::make('category.name')->label('القسم الرئيسي'),
//                Tables\Columns\TextColumn::make('user.name')->label('المتجر')->url(fn($record) => UserResource::getUrl('edit', ['record' => $record->user_id])),
                Tables\Columns\TextColumn::make('end_date')->label('نهاية التقديم'),
                Tables\Columns\TextColumn::make('views_count')->label('عدد المشاهدات'),
                Tables\Columns\TextColumn::make('created_at')->since()->label('أضيف منذ'),
                Tables\Columns\TextColumn::make('active')->formatStateUsing(fn($state) => ProductActiveEnum::tryFrom($state)?->getLabel())->color(fn($state) => ProductActiveEnum::tryFrom($state)?->getColor())->icon(fn($state) => ProductActiveEnum::tryFrom($state)?->getIcon())->label('الحالة'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }
}
