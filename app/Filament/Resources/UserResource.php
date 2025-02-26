<?php

namespace App\Filament\Resources;

use App\Enums\CommunityTypeEnum;

use App\Enums\LevelSellerEnum;
use App\Enums\LevelUserEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Helpers\HelperMedia;
use App\Helpers\HelpersEnum;
use App\Models\Balance;
use App\Models\City;
use App\Models\Community;
use App\Models\Message;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use libphonenumber\PhoneNumberType;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

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
                Forms\Components\Grid::make()->schema([
                    Forms\Components\Wizard::make([
                        Forms\Components\Wizard\Step::make('بيانات المستخدم')->schema([
                            Forms\Components\Fieldset::make('بيانات المستخدم')->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->conversion('webp')
                                    ->openable()
                                    ->label('صورة')->imageEditor()->imageCropAspectRatio('1:1'),

                                Forms\Components\TextInput::make('name')->required()->label('الاسم'),
                                Forms\Components\TextInput::make('email')->required()->email()->unique(ignoreRecord: true)->label('البريد الإلكتروني'),
                                Forms\Components\TextInput::make('password')->required(fn($context) => $context === 'create')
                                    ->dehydrateStateUsing(fn($state) => \Hash::make($state))->dehydrated(fn($state) => filled($state))->password()
                                    ->same('passwordConfirmation')
                                    ->label('كلمة المرور'),
                                Forms\Components\TextInput::make('passwordConfirmation')->required(fn($context) => $context === 'create')
                                    ->dehydrated(false)->password()
                                    ->label('تأكيد كلمة المرور'),

                                /*  PhoneInput::make('phone')
                                      ->countryStatePath('country_code')
                                      ->validateFor(

                                          type: PhoneNumberType::MOBILE , // default: null
                                          lenient: true, // default: false
                                      )->displayNumberFormat(PhoneInputNumberType::E164)->formatOnDisplay(false)->formatAsYouType(true)->label('رقم الهاتف'),
                                 */
                                Forms\Components\TextInput::make('phone')->label('رقم الهاتف')->required(),
                                Forms\Components\TextInput::make('affiliate')->label('كود الإحالة')->readOnly()->visible(fn($context) => $context != 'create'),

//                        Forms\Components\DatePicker::make('upgrade_date')/*->required(fn($get) => $get('plan') != null)*/ ->label('تاريخ آخر ترقية'),
                                Forms\Components\DatePicker::make('email_verified_at')->label('حدد تاريخ لتأكيد الحساب'),
                                Forms\Components\Select::make('city_id')->options(City::pluck('name', 'id'))->label('المدينة'),
                                Forms\Components\Select::make('level')->options([
                                    LevelUserEnum::ADMIN->value => LevelUserEnum::ADMIN->getLabel(),
                                    LevelUserEnum::SELLER->value => LevelUserEnum::SELLER->getLabel(),
                                    LevelUserEnum::USER->value => LevelUserEnum::USER->getLabel(),
                                    LevelUserEnum::STAFF->value => LevelUserEnum::STAFF->getLabel(),
                                ]),
                                Forms\Components\Select::make('roles')->relationship('roles', 'name')->multiple()->label('الأدوار'),
                                Forms\Components\Toggle::make('is_active')->label('حالة المستخدم'),
                                Forms\Components\Toggle::make('is_seller')->label('تفعيل المتجر')->live(),
                            ]),
                        ]),
                        Forms\Components\Wizard\Step::make('بيانات المتجر')->schema([

                            Forms\Components\Fieldset::make('بيانات المتجر')->schema([
                                Forms\Components\TextInput::make('seller_name')->label('اسم المتجر'),
                                Forms\Components\TextInput::make('address')->label('عنوان المتجر'),
                                Forms\Components\Textarea::make('info')->label('وصف مختصر')->columnSpan(2),
                                Forms\Components\SpatieMediaLibraryFileUpload::make('logo')->collection('logo')->conversion('webp')->label('صورة Cover')->imageEditor()->imageCropAspectRatio('1:2')
                                    ->openable()->columnSpan(2),
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Toggle::make('is_default_active')->label('تفعيل المنتجات تلقائيا'),
                                    Forms\Components\Toggle::make('is_delivery')->label('خدمة التوصيل'),
                                    Forms\Components\Grid::make()->schema([
                                        Forms\Components\Toggle::make('can_create_channel')->label('إنشاء قناة'),
                                        Forms\Components\TextInput::make('count_channel')->label('عدد القنوات'),
                                    ]),
                                    Forms\Components\Grid::make()->schema([
                                        Forms\Components\Toggle::make('can_create_group')->label('إنشاء مجموعات'),
                                        Forms\Components\TextInput::make('count_group')->label('عدد المجموعات'),
                                    ])
                                ]),
                                Forms\Components\TimePicker::make('open_time')->label('يفتح من الساعة'),
                                Forms\Components\TimePicker::make('close_time')->label('يغلق في الساعة'),
                                Forms\Components\Fieldset::make('متجر مميز')->schema([
                                    Forms\Components\Grid::make(3)->schema([
                                        Forms\Components\Toggle::make('is_special')->label('تمييز المتجر')->inline(false)->live()->hint('عند تفعيل هذا الخيار سيظهر المتجر في الصفحة الرئيسية'),
                                        HelperMedia::getFileUpload('صورة مميزة', 'custom', 'custom', false, ['2:1'])->required(fn($get) => $get('is_special')),
                                        Forms\Components\Select::make('category_id')->relationship('category', 'name')->label('القسم')->searchable(),

                                    ])
                                ]),
                                Forms\Components\Toggle::make('is_verified')->label('توثيق المتجر'),
                                Forms\Components\DatePicker::make('verified_account_date')->label('تاريخ إنتهاء التوثيق'),
                                Forms\Components\ColorPicker::make('id_color')->label('هوية المتجر')->default("#FF0000"),
                            ]),

                        ])->visible(fn($get) => $get('is_seller')),
                        Forms\Components\Wizard\Step::make('معلومات مواقع التواصل')->schema([
                            Forms\Components\TextInput::make('social.instagram')->label('رابط إنستغرام')->nullable()->url()->placeholder('https://'),
                            Forms\Components\TextInput::make('social.face')->label('رابط فيسبوك')->nullable()->url()->placeholder('https://'),
                            Forms\Components\TextInput::make('social.linkedin')->label('رابط لينكدن')->nullable()->url()->placeholder('https://'),
                            Forms\Components\TextInput::make('social.tiktok')->label('رابط تيك توك')->nullable()->url()->placeholder('https://'),
                            Forms\Components\TextInput::make('social.twitter')->label('رابط تويتر')->nullable()->url()->placeholder('https://'),
                            //Forms\Components\TextInput::make('social.telegram')->label('رابط تلغرام')->nullable()->url()->placeholder('https://'),
                        ])->visible(fn($get) => $get('is_seller')),
                        Forms\Components\Wizard\Step::make('معرض الصور')->schema([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')->collection('gallery')->image()->imageCropAspectRatio('1:1')->imageEditor()->multiple()->label('صور المعرض')
                        ])->visible(fn($get) => $get('is_seller')),
                    ])->skippable()->columnSpan(2)
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->latest())
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->searchable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image')->conversion('webp')->label('صورة المستخدم')->circular()->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('level')->formatStateUsing(fn($state) => LevelUserEnum::tryFrom($state)->getLabel())->icon(fn($state) => LevelUserEnum::tryFrom($state)->getIcon())->color(fn($state) => LevelUserEnum::tryFrom($state)->getColor())->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('name')->label('الاسم')->toggleable(isToggledHiddenByDefault: false)->searchable(),
                /* PhoneColumn::make('phone')
                     ->countryColumn('country_code')->displayFormat(PhoneInputNumberType::E164)->url(fn($state)=>'https://wa.me/'.\Str::replace(' ','',ltrim($state),'+'),true),
                */ Tables\Columns\TextColumn::make('phone')
                    ->formatStateUsing(function ($state) {
                        $phone = $state;
                        if (\Str::startsWith($phone, '+')) {
                            $phone = \Str::substr($phone, 1, \Str::length($phone) - 1);
                        } elseif (\Str::startsWith($phone, '00')) {
                            $phone = \Str::substr($phone, 2, \Str::length($phone) - 1);
                        }
                        return $phone;
                    })
                    ->url(function ($record) {
                        $phone = $record->phone;
                        if (\Str::startsWith($phone, '+')) {
                            $phone = \Str::substr($phone, 1, \Str::length($phone) - 1);
                        } elseif (\Str::startsWith($phone, '00')) {
                            $phone = \Str::substr($phone, 2, \Str::length($phone) - 1);
                        }
                        return 'https://wa.me/' . $phone;
                    }, true)
                    ->formatStateUsing(fn($record) => $record->country_code . $record->phone)
                    ->label('رقم الهاتف')->toggleable(isToggledHiddenByDefault: false)->searchable()->sortable(),
                Tables\Columns\TextColumn::make('city.name')->label('المدينة')->toggleable(isToggledHiddenByDefault: false)->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->label('البريد الإلكتروني')->toggleable(isToggledHiddenByDefault: false)->searchable(),
                Tables\Columns\TextColumn::make('seller_name')->label('اسم المتجر')->toggleable(isToggledHiddenByDefault: false)->searchable(),
                Tables\Columns\TextColumn::make('products_count')->label('عدد المنتجات')->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('is_verified_email')->label('حالة التأكيد')->formatStateUsing(fn($state) => HelpersEnum::getEmailVerified($state, 'label'))
                    ->icon(fn($state) => HelpersEnum::getEmailVerified($state, 'icon'))
                    ->color(fn($state) => HelpersEnum::getEmailVerified($state, 'color'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('is_special')->label('متجر مميز')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('level_seller')->label('نوع الإشتراك')
                    ->formatStateUsing(fn($state) => LevelSellerEnum::tryFrom($state)->getLabel())
                    ->color(fn($state) => LevelSellerEnum::tryFrom($state)->getColor())
                    ->toggleable(isToggledHiddenByDefault: true)->searchable(),

                Tables\Columns\TextColumn::make('created_at')->date('Y-m-d')->label('تاريخ التسجيل')->toggleable(isToggledHiddenByDefault: false)->sortable(),
            ])
            ->filters([
                /*Tables\Filters\TernaryFilter::make('is_seller')->falseLabel('مستخدم')->trueLabel('متجر')->queries(
                    true: fn($query) => $query->where('is_seller', 1),
                    false: fn($query) => $query->where('is_seller', 0),
                    blank: fn($query) => $query,
                )->label('نوع المستخدم'),*/
                Tables\Filters\Filter::make('filter')->form([
                    Forms\Components\Select::make('level')->options([
                        LevelUserEnum::SELLER->value => LevelUserEnum::SELLER->getLabel(),
                        LevelUserEnum::USER->value => LevelUserEnum::USER->getLabel(),
                        LevelUserEnum::ADMIN->value => LevelUserEnum::ADMIN->getLabel(),

                    ])->label('نوع المستخدم'),
                    Forms\Components\Select::make('city')->options(City::where('is_main', true)->pluck('name', 'id'))->label('المحافظة')->live(),
                    Forms\Components\Select::make('city_id')->options(fn($get) => City::where('city_id', $get('city'))->pluck('name', 'id'))->label('المدينة')

                ])->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['level'],
                            fn(Builder $query, $value): Builder => $query->where('level', $value),
                        )
                        ->when(
                            $data['city_id'],
                            fn(Builder $query, $value): Builder => $query->where('city_id', $value),
                        )->when(
                            $data['city'],
                            fn(Builder $query, $value): Builder => $query->whereHas('city', fn($query) => $query->where('cities.city_id', $value)),
                        );
                })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ActionGroup::make([
                    /* add balance */
                    Tables\Actions\Action::make('add_balance')->form([
                        Forms\Components\TextInput::make('value')->label('القيمة')->required()->gt(0),
                        Forms\Components\TextInput::make('info')->label('ملاحظات')
                    ])
                        ->action(function ($record, $data) {
                            Balance::create([
                                'credit' => $data['value'],
                                'debit' => 0,
                                'info' => $data['info'],
                                'user_id' => $record->id
                            ]);
                            Notification::make('success')->success()->title('نجاح')->body('تم إضافة الرصيد بنجاح')->send();
                        })->label('إضافة رصيد')->icon('fas-hand-holding-dollar'),
                    /* sub balance */
                    Tables\Actions\Action::make('sub_balance')->form([
                        Forms\Components\TextInput::make('value')->label('القيمة')->required()->gt(0),
                        Forms\Components\TextInput::make('info')->label('ملاحظات')
                    ])
                        ->action(function ($record, $data) {
                            Balance::create([
                                'credit' => 0,
                                'debit' => $data['value'],
                                'info' => $data['info'],
                                'user_id' => $record->id
                            ]);
                            Notification::make('success')->success()->title('نجاح')->body('تم السحب من الرصيد بنجاح')->send();
                        })->label('سحب من الرصيد')->icon('fas-cash-register'),
                    /* send msg chat */
                    Tables\Actions\Action::make('send_msg_chat')->form([
                        Forms\Components\Textarea::make('msg')->label('الرسالة')->required(),
                    ])
                        ->action(function ($record, $data) {
                            \DB::beginTransaction();
                            try {
                                $community = Community::where('type', CommunityTypeEnum::CHAT->value)->whereHas('users', fn($query) => $query->whereIn('users.id', [auth()->id(), $record->id]))->first();
                                if ($community == null) {
                                    $community = Community::create([
                                        'name' => auth()->user()->name . ' - ' . $record->name,
                                        'manager_id' => auth()->id(),
                                        'type' => CommunityTypeEnum::CHAT->value,
                                        'last_update' => now(),
                                        'is_global' => false,
                                    ]);
                                    $community->users()->sync([auth()->id(), $record->id]);
                                }
                                Message::create([
                                    'community_id' => $community->id,
                                    'user_id' => auth()->id(),
                                    'body' => $data['msg'],
                                    'type' => 'text',
                                ]);
                                \DB::commit();
                                Notification::make('success')->title('نجاح العملية')->body('تم إرسال الرسالة بنجاح')->success()->send();

                            } catch (\Exception | \Error $e) {
                                \DB::rollBack();
                                Notification::make('error')->title('فشل العملية')->body($e->getMessage())->danger()->send();

                            }
                        })->label('إرسال رسالة')->icon('fas-envelope'),
                    /* email verified */
                    Tables\Actions\Action::make('email_verified_at')
                        ->action(fn($record) => $record->update(['email_verified_at' => now()]))
                        ->icon('fas-circle-check')
                        ->label('تأكيد البريد'),
                    /* Add to Community */
                    Tables\Actions\Action::make('add_to_community')->form([
                        Forms\Components\Select::make('communities')->options(Community::whereNot('type', CommunityTypeEnum::CHAT->value)->pluck('name', 'id'))->multiple()->searchable()
                            ->label('المجتمع')
                    ])
                        ->action(function ($record, $data) {
                            /**
                             * @var $record User
                             */
                            $record->communities()->sync($data['communities']);
                            Notification::make('success')->success()->title('نجاح العملية')->body('تم إضافة المستخدم إلى المجتمعات')->send();
                        })->label('إضافة إلى مجتمع')
                ]),
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()->fromTable()->withChunkSize(200)->askForFilename()
                        ->withFilename(fn($filename) => 'ali-pasha-' . $filename),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('add_to_community')->form([
                        Forms\Components\Select::make('communities')->options(Community::whereNot('type', CommunityTypeEnum::CHAT->value)->pluck('name', 'id'))->multiple()->searchable()
                            ->label('المجتمع')
                    ])
                        ->action(function ($records, $data) {

                            foreach ($records as $record) {
                                /**
                                 * @var $record User
                                 */
                                $record->communities()->sync($data['communities']);
                            }

                            Notification::make('success')->success()->title('نجاح العملية')->body('تم إضافة المستخدم إلى المجتمعات')->send();
                        })->label('إضافة إلى مجتمع')
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductsRelationManager::class,
            RelationManagers\BalancesRelationManager::class,
            RelationManagers\PlansRelationManager::class,
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
