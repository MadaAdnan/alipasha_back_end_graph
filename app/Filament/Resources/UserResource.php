<?php

namespace App\Filament\Resources;

use App\Enums\CommunityTypeEnum;

use App\Enums\LevelSellerEnum;
use App\Enums\LevelUserEnum;
use App\Filament\Exports\UserExporter;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Helpers\HelperMedia;
use App\Helpers\HelpersEnum;
use App\Jobs\SendFirebaseNotificationJob;
use App\Jobs\WebhokProductsJob;
use App\Jobs\WebhokUserJob;
use App\Models\Balance;
use App\Models\Category;
use App\Models\City;
use App\Models\Community;
use App\Models\Country;
use App\Models\Interaction;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use App\Notifications\UserNotification;
use App\Service\SendNotifyHelper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
                                    ->autocomplete('new-password')
                                    ->dehydrateStateUsing(fn($state) => \Hash::make($state))->dehydrated(fn($state) => filled($state))->password()
                                    ->same('passwordConfirmation')
                                    ->label('كلمة المرور'),
                                Forms\Components\TextInput::make('passwordConfirmation')->required(fn($context) => $context === 'create')
                                    ->dehydrated(false)->password()
                                    ->label('تأكيد كلمة المرور'),


                                Forms\Components\Grid::make(5)->schema([
                                    Forms\Components\Select::make('phone_code')->options(Country::all()->mapWithKeys(fn($el) => [
                                        $el->code => "{$el->name} - {$el->code}"
                                    ])->toArray()
                                    )->label('الدولة')->searchable(),
                                    Forms\Components\TextInput::make('phone')->label('رقم الهاتف')->required()->columnSpan(4)
                                ]),
                                Forms\Components\TextInput::make('affiliate')->label('كود الإحالة')->readOnly()->visible(fn($context) => $context != 'create'),

//                        Forms\Components\DatePicker::make('upgrade_date')/*->required(fn($get) => $get('plan') != null)*/ ->label('تاريخ آخر ترقية'),
                                Forms\Components\DatePicker::make('email_verified_at')->label('حدد تاريخ لتأكيد الحساب'),
                                Forms\Components\Select::make('city_id')->options(City::where('is_main', true)->pluck('name', 'id'))->label('المحافظة')->reactive()->searchable()->searchDebounce(750),
                                Forms\Components\Select::make('area_id')->options(fn($get) => City::where('city_id', $get('city_id'))->pluck('name', 'id'))->label('المنطقة')->searchable()->searchDebounce(750),
                                Forms\Components\Select::make('level')->options([
                                    LevelUserEnum::ADMIN->value => LevelUserEnum::ADMIN->getLabel(),
                                    LevelUserEnum::SELLER->value => LevelUserEnum::SELLER->getLabel(),
                                    LevelUserEnum::USER->value => LevelUserEnum::USER->getLabel(),
                                    LevelUserEnum::STAFF->value => LevelUserEnum::STAFF->getLabel(),
                                ]),
                                Forms\Components\Fieldset::make('توثيق الحساب')->schema([
                                    Forms\Components\Toggle::make('is_verified')->label('توثيق المتجر'),
                                    Forms\Components\DatePicker::make('verified_account_date')->label('تاريخ إنتهاء التوثيق'),
                                ]),
                                Forms\Components\Select::make('roles')->relationship('roles', 'name')->multiple()->label('الأدوار')->visible(auth()->user()->hasRole('super_admin')),
                                Forms\Components\Toggle::make('is_active')->label('حالة المستخدم')->inlineLabel(false)->inline(false),
                                Forms\Components\Toggle::make('is_seller')->label('تفعيل المتجر')->live()->visible(auth()->user()->hasRole('super_admin')),
                            ]),
                        ]),
                        Forms\Components\Wizard\Step::make('بيانات المتجر')->schema([

                            Forms\Components\Fieldset::make('بيانات المتجر')->schema([
                                Forms\Components\TextInput::make('seller_name')->label('اسم المتجر'),
                                Forms\Components\TextInput::make('address')->label('عنوان المتجر'),
                                Forms\Components\Textarea::make('info')->label('وصف مختصر')->columnSpan(2),
                                Forms\Components\SpatieMediaLibraryFileUpload::make('logo')->collection('logo')->conversion('webp')->label('صورة Cover')->imageEditor()->imageCropAspectRatio('2:1')
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

                                Forms\Components\ColorPicker::make('id_color')->label('هوية المتجر')->default("#FF3B30FF"),
                                Forms\Components\Group::make()->schema([
                                    Forms\Components\TextInput::make('url_webhok')->url()->label('رابط ويب هوك الخاص بالمتجر')
                                    ->suffixAction(Forms\Components\Actions\Action::make('sync')->action(function ($record) {
                                        $job = new WebhokUserJob($record);
                                        dispatch($job);
                                        $products = Product::where([
                                            'user_id' => $record->id,
                                            'is_sync_webhok' => false,
                                        ])->get();

                                        $job2 = new WebhokProductsJob($products, $record->url_webhok);
                                        dispatch($job2);
                                    }
                                    )->icon('heroicon-o-link'))
                                    ,
                                    Forms\Components\TextInput::make('business_email')->email()->label('البريد الإلكتروني الخاص بمتجرك'),
                                ]),
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
            ->searchDebounce(750)
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->searchable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image')->conversion('webp')->label('صورة المستخدم')->circular()->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('level')->formatStateUsing(fn($state) => LevelUserEnum::tryFrom($state)->getLabel())->icon(fn($state) => LevelUserEnum::tryFrom($state)->getIcon())->color(fn($state) => LevelUserEnum::tryFrom($state)->getColor())->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('name')->label('الاسم')->toggleable(isToggledHiddenByDefault: false)->searchable(),
                /* PhoneColumn::make('phone')
                     ->countryColumn('country_code')->displayFormat(PhoneInputNumberType::E164)->url(fn($state)=>'https://wa.me/'.\Str::replace(' ','',ltrim($state),'+'),true),
                */
                Tables\Columns\TextColumn::make('full_phone')

                    ->url(function ($state) {

                        return 'https://wa.me/' . "{$state}";
                    }, true)
                    ->label('رقم الهاتف')->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('city.name')->label('المدينة')->toggleable(isToggledHiddenByDefault: false)->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->label('البريد الإلكتروني')->toggleable(isToggledHiddenByDefault: false)->searchable(),
                Tables\Columns\TextColumn::make('seller_name')->label('اسم المتجر')->toggleable(isToggledHiddenByDefault: false)->searchable(),
                Tables\Columns\TextColumn::make('products_count')->label('عدد المنتجات')->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('category.name')->label('التصنيف')->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('is_verified_email')->label('حالة التأكيد')->formatStateUsing(fn($state) => HelpersEnum::getEmailVerified($state, 'label'))
                    ->icon(fn($state) => HelpersEnum::getEmailVerified($state, 'icon'))
                    ->color(fn($state) => HelpersEnum::getEmailVerified($state, 'color'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('is_special')->label('متجر مميز')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('clicks_count')->label('عدد التواصلات')->toggleable(isToggledHiddenByDefault: true)->sortable(),
                Tables\Columns\TextColumn::make('level_seller')->label('نوع الإشتراك')
                    ->formatStateUsing(fn($state) => LevelSellerEnum::tryFrom($state)->getLabel())
                    ->color(fn($state) => LevelSellerEnum::tryFrom($state)->getColor())
                    ->toggleable(isToggledHiddenByDefault: true)->searchable(),
                Tables\Columns\TextColumn::make('points')->formatStateUsing(fn($record) => $record->getTotalPoint())->label('النقاط')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('balances')->formatStateUsing(fn($record) => $record->getTotalBalance())->label('الرصيد')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->date('Y-m-d')->label('تاريخ التسجيل')->toggleable(isToggledHiddenByDefault: false)->sortable(),

            ])
            ->filters([

                Tables\Filters\Filter::make('filter')->form([
                    Forms\Components\Select::make('level')->options([
                        LevelUserEnum::SELLER->value => LevelUserEnum::SELLER->getLabel(),
                        LevelUserEnum::USER->value => LevelUserEnum::USER->getLabel(),
                        LevelUserEnum::ADMIN->value => LevelUserEnum::ADMIN->getLabel(),

                    ])->label('نوع المستخدم'),
                    Forms\Components\Select::make('city_id')->options(City::where('is_main', true)->pluck('name', 'id'))->label('المحافظة')->live(),
                    Forms\Components\Select::make('category_id')->options(Category::where('is_main', true)->pluck('name', 'id'))->label('التصنيف')->live(),
                    Forms\Components\Select::make('area_id')->options(fn($get) => City::where('city_id', $get('city_id'))->pluck('name', 'id'))->label('المدينة'),
                    Forms\Components\Select::make('phone')->options([
                        'all' => 'الكل',
                        'notUse' => 'لا يملك هاتف',
                        'use' => 'يملك هاتف',
                    ])->label('الهاتف')

                ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['level'],
                                fn(Builder $query, $value): Builder => $query->where('level', $value),
                            )->when(
                                $data['category_id'],
                                fn(Builder $query, $value): Builder => $query->where('category_id', $value),
                            )
                            ->when(
                                $data['city_id'],
                                fn(Builder $query, $value): Builder => $query->where('city_id', $value),
                            )
                            ->when(
                                $data['area_id'],
                                fn(Builder $query, $value): Builder => $query->where('area_id', $value),
                            )
                            ->when(
                                $data['phone'] == 'notUse',
                                fn(Builder $query, $value): Builder => $query->whereNull('phone'),
                            )->when(
                                $data['phone'] == 'use',
                                fn(Builder $query, $value): Builder => $query->whereNotNull('phone'),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ActionGroup::make([
                    // sync products
                    Tables\Actions\Action::make('sync_products')->action(function ($record) {
                        /*$products = Product::where([
                            'user_id' => $record->id,
                            'is_sync_webhok' => false,
                        ])->get();

                        $job = new WebhokProductsJob($products, $record->url_webhok);
                        dispatch($job);*/
                        User::where('id',$record->id)->whereNotNull('url_webhok')
                            ->whereHas('products', fn($q) => $q->where('is_sync_webhok', false))
                            ->each(function ($user) {
                                Product::where('user_id', $user->id)
                                    ->where('is_sync_webhok', false)
                                    ->chunk(30, function ($products) use ($user) {
                                        try {
                                            dispatch(new WebhokProductsJob($products, $user->url_webhok));

                                        } catch (\Throwable $e) {
                                            \Log::error("Error dispatching job: " . $e->getMessage());
                                        }
                                    });
                            });
                    })->visible(fn($record) => \Str::isUrl($record->url_webhok) && $record->products()->where('products.is_sync_webhok', false)->count() > 0)->label('مزامنة المنتجات'),


                    Tables\Actions\Action::make('sync_user')->action(function ($record) {
                        $job = new WebhokUserJob($record);
                        dispatch($job);
                    })->visible(fn($record) => \Str::isUrl($record->url_webhok) && $record->is_sync_webhok==false)->label('مزامنة الإعدادات'),
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
                            $community = Community::where('type', CommunityTypeEnum::CHAT->value)->whereHas('users', fn($query) => $query->whereIn('users.id', [auth()->id(), $record->id]))->first();
                            dd(auth()->id(), $record);
                            \DB::beginTransaction();
                            try {
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

                            } catch (\Exception|\Error $e) {
                                \DB::rollBack();
                                Notification::make('error')->title('فشل العملية')->body($e->getMessage())->danger()->send();

                            }
                        })->label('إرسال رسالة إلى الشات')->icon('fas-envelope'),
                    /*send firebase*/
                    /*Tables\Actions\Action::make('send_msg_chat_firebase')->form([
                        Forms\Components\Textarea::make('msg')->label('الرسالة')->required(),
                    ])
                        ->action(function ($record, $data) {
                            try {
                                $data['title'] = 'تطبيق علي باشا';
                                $data['body'] = $data['msg'];
                                $data['url'] = 'https://v3.ali-pasha.com';

                                SendNotifyHelper::sendNotifyMultiUser(collect($record), $data);

                                Notification::make('success')->title('نجاح العملية')->body('تم إرسال الرسالة بنجاح')->success()->send();

                            } catch (\Exception|\Error $e) {

                                Notification::make('error')->title('فشل العملية')->body($e->getMessage())->danger()->send();

                            }
                        })->label('رسالة FireBase')->icon('fas-comment'),*/
                    /* email verified */
                    Tables\Actions\Action::make('email_verified_at')
                        ->action(fn($record) => $record->update(['email_verified_at' => now()]))
                        ->icon('fas-circle-check')
                        ->label('تأكيد البريد'),
                    /* Add to Community */
                    Tables\Actions\Action::make('add_to_community')->form([
                        Forms\Components\Select::make('communities')->options(Community::where('type', '!=', CommunityTypeEnum::CHAT->value)->where('type', '!=', CommunityTypeEnum::LIVE->value)->pluck('name', 'id'))->multiple()->searchable()
                            ->label('المجتمع')
                    ])
                        ->action(function ($record, $data) {
                            /**
                             * @var $record User
                             */
                            $record->communities()->sync($data['communities'], false);
                            Notification::make('success')->success()->title('نجاح العملية')->body('تم إضافة المستخدم إلى المجتمعات')->send();
                        })->label('إضافة إلى مجتمع'),
                    Tables\Actions\Action::make('delete_recommended')
                        ->action(fn($record) => Interaction::where('user_id', $record->id)->delete())
                        ->label('حذف الإهتمامات')->requiresConfirmation()
                ]),
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()->modifyQueryUsing(fn (Builder $query) => User::query()
                        ->select('id', 'name', 'full_phone','email','seller_name','email_verified_at', 'created_at') // إختر فقط الحقول اللازمة
                        ->orderBy('id')),
                     ExcelExport::make()
                         ->fromTable()            // يبقى fromTable لكن سيأخذ الـ query المعدّل أعلاه
                         ->withChunkSize(500)     // حجم الـ chunk لمعالجة أقل ذاكرة
                         ->queue()                // ضع التصدير في queue لأن العملية ثقيلة
                         ->askForFilename()
                         ->withFilename(fn($filename) => 'ali-pasha-' . $filename),
                ])->visible(/*auth()->user()->can('export_users')*/true),
                Tables\Actions\Action::make('send_msg_phone')->form([
                    Forms\Components\TextInput::make('title')->label('العنوان')->required(),
                    Forms\Components\Textarea::make('msg')->label('الرسالة')->required(),
                ])
                    ->action(function ($data) {
                        try {
                            $dataMsg['title'] = $data['title'];
                            $dataMsg['body'] = $data['msg'];
                            $dataMsg['url'] = 'https://v3.ali-pasha.com';
                            User::whereNull('phone')->chunk(100, function ($users) use ($dataMsg) {
                                foreach ($users as $user) {
                                    SendNotifyHelper::sendNotify($user, $dataMsg);
                                }
                            });

                            Notification::make('success')->title('نجاح العملية')->body('تم إرسال الرسالة بنجاح')->success()->send();

                        } catch (\Exception|\Error $e) {

                            Notification::make('error')->title('فشل العملية')->body($e->getMessage())->danger()->send();

                        }
                    })->label('رسالة SMS للجميع')->icon('fas-comment'),
                Tables\Actions\Action::make('send_msg_verified')->form([
                    Forms\Components\TextInput::make('title')->label('العنوان')->required(),
                    Forms\Components\Textarea::make('msg')->label('الرسالة')->required(),
                ])
                    ->action(function ($data) {
                        try {
                            $dataMsg['title'] = $data['title'];
                            $dataMsg['body'] = $data['msg'];
                            $dataMsg['url'] = 'https://v3.ali-pasha.com';
                            User::whereNull('email_verified_at')->chunk(100, function ($users) use ($dataMsg) {
                                foreach ($users as $user) {
                                    SendNotifyHelper::sendNotify($user, $dataMsg);
                                }
                            });

                            Notification::make('success')->title('نجاح العملية')->body('تم إرسال الرسالة بنجاح')->success()->send();

                        } catch (\Exception|\Error $e) {

                            Notification::make('error')->title('فشل العملية')->body($e->getMessage())->danger()->send();

                        }
                    })->label('رسالة لجميع امستخدمين المؤكدين')->icon('fas-comment'),
                Tables\Actions\Action::make('delete_recommended')
                    ->action(fn() => Interaction::where('id', '!=', 0)->delete())
                    ->label('حذف الإهتمامات')->requiresConfirmation()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('add_to_community')->form([
                        Forms\Components\Select::make('communities')->options(Community::where('type', '!=', CommunityTypeEnum::CHAT->value)->where('type', '!=', CommunityTypeEnum::LIVE->value)->pluck('name', 'id'))->multiple()->searchable()
                            ->label('المجتمع')
                    ])
                        ->action(function ($records, $data) {

                            foreach ($records as $record) {
                                /**
                                 * @var $record User
                                 */
                                $record->communities()->sync($data['communities'], false);
                            }

                            Notification::make('success')->success()->title('نجاح العملية')->body('تم إضافة المستخدم إلى المجتمعات')->send();
                        })->label('إضافة إلى مجتمع'),

                    Tables\Actions\BulkAction::make('send_msg')->form([
                        Forms\Components\TextInput::make('title')->label('العنوان')->required(),
                        Forms\Components\Textarea::make('msg')->label('الرسالة')->required(),
                    ])
                        ->action(function ($records, $data) {
                            try {
                                $dataMsg['title'] = $data['title'];
                                $dataMsg['body'] = $data['msg'];
                                $dataMsg['url'] = 'https://web.ali-pasha.com';

                                SendNotifyHelper::sendNotifyMultiUser($records, $dataMsg);
/*\Notification::send($records, new UserNotification($dataMsg));*/

                                Notification::make('success')->title('نجاح العملية')->body('تم إرسال الرسالة بنجاح')->success()->send();

                            } catch (\Exception|\Error $e) {

                                Notification::make('error')->title('فشل العملية')->body($e->getMessage())->danger()->send();

                            }
                        })->label('رسالة FirBase ')->icon('fas-comment')
                ]),
                Tables\Actions\ExportBulkAction::make("ExportAction")
                    ->exporter(UserExporter::class)->label('تصدير')
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductsRelationManager::class,
            RelationManagers\BalancesRelationManager::class,
            RelationManagers\PlansRelationManager::class,
            RelationManagers\CommunitiesRelationManager::class,
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
