<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = -2;
    protected static ?string $label = 'إعدادات';
    protected static ?string $modelLabel = 'إعدادات';
    protected static ?string $navigationLabel = 'الإعدادات';
    protected static ?string $pluralLabel = 'الإعدادات';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?string $slug='settings';
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
        return  auth()->user()?->can('view_any_setting'); // TODO: Change the autogenerated stub
    }

    public static function canCreate(): bool
    {
        return  auth()->user()?->can('create_setting') && Setting::count() === 0; // TODO: Change the autogenerated stub
    }

    public static function canDelete(Model $record): bool
    {
        return  auth()->user()?->can('delete_setting'); // TODO: Change the autogenerated stub
    }

    public static function canDeleteAny(): bool
    {
        return  auth()->user()?->can('delete_setting'); // TODO: Change the autogenerated stub
    }

    public static function canView(Model $record): bool
    {
        return  auth()->user()?->can('view_any_setting'); // TODO: Change the autogenerated stub
    }

    public static function canViewAny(): bool
    {
        return  auth()->user()?->can('view_any_setting'); // TODO: Change the autogenerated stub
    }

    public static function canRestoreAny(): bool
    {
        return  auth()->user()?->can('restore_setting'); // TODO: Change the autogenerated stub
    }

    public static function canEdit(Model $record): bool
    {
        return  auth()->user()?->can('update_setting'); // TODO: Change the autogenerated stub
    }

    public static function canForceDeleteAny(): bool
    {
        return  auth()->user()?->can('force_delete_setting'); // TODO: Change the autogenerated stub
    }

    public static function canRestore(Model $record): bool
    {
        return  auth()->user()?->can('restore_setting'); // TODO: Change the autogenerated stub
    }

    public static function canForceDelete(Model $record): bool
    {
        return  auth()->user()?->can('force_delete_setting'); // TODO: Change the autogenerated stub
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الإعدادات')->schema([
                    Forms\Components\Wizard::make([
                        Forms\Components\Wizard\Step::make('معلومات الموقع')->schema([
//                            Forms\Components\SpatieMediaLibraryFileUpload::make('sliders')->multiple()->imageCropAspectRatio(),
                            Forms\Components\SpatieMediaLibraryFileUpload::make('logo')->collection('logo')->conversion('webp')->label('أيقونة الموقع')->image()->imageCropAspectRatio('1:1')->imageEditor(),
                            Forms\Components\SpatieMediaLibraryFileUpload::make('white-logo')->collection('white-logo')->conversion('webp')->label('لوغو الفوتر')->image()->imageCropAspectRatio('1:1')->imageEditor(),
                            Forms\Components\TextInput::make('social.name')->label('اسم الموقع'),
                            Forms\Components\TextInput::make('social.email')->label('بريد الموقع الرئيسي')->nullable()->email(),
                            Forms\Components\TextInput::make('social.sub_email')->label('بريد الموقع الثانوي')->nullable()->email(),
                            Forms\Components\TextInput::make('social.phone')->label('الهاتف الرئيسي'),
                            Forms\Components\TextInput::make('social.sub_phone')->label('الهاتف الثانوي'),
                            Forms\Components\TextInput::make('address')->label('العنوان'),
                            Forms\Components\TextInput::make('longitude')->label('خط الطول')->nullable()->numeric(),
                            Forms\Components\TextInput::make('latitude')->label('خط العرض')->nullable()->numeric(),
                            Forms\Components\TextInput::make('weather_api')->label('Api الطقس')->nullable(),
                            Forms\Components\Select::make('plan_id')->relationship('plan', 'name')->searchable()->preload()->label('الخطة الإفتراضية للمستخدمين الجدد'),

                        ]),

                        Forms\Components\Wizard\Step::make('معلومات التطبيق')->schema([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('apk')->collection('apk')->label('رفع التطبيق')->preserveFilenames(),
                            Forms\Components\Toggle::make('send_notification_hobbies')->label('حالة إشعارات الاهتمامات'),

                            Forms\Components\TextInput::make('current_version')->required()->label('الإصدار الحالي من التطبيق'),
                            Forms\Components\Toggle::make('force_upgrade')->label('حالة المطالبة بالتحديث'),
                            Forms\Components\TextInput::make('whats_msg')->nullable()->label('رسالة واتس آب'),
                            Forms\Components\TextInput::make('url_for_download.play')->url()->label('رابط تحميل من GooglePlay'),
                            Forms\Components\TextInput::make('url_for_download.up_down')->url()->label('رابط تحميل من UpToDown'),
                            Forms\Components\Section::make('إعلان بداية التطبيق')->schema([
                                Forms\Components\Grid::make()->schema([
                                    Forms\Components\SpatieMediaLibraryFileUpload::make('advice')->collection('advice')->image()->imageEditor()->imageEditorAspectRatios(['1:1', "2:1"])->label('صورة إعلان بداية التطبيق'),
                                    Forms\Components\TextInput::make('advice_url')->label('رابط الإعلان')->url()->prefix('https://'),
                                    Forms\Components\Toggle::make('active_advice')->label('حالة الإعلان')
                                ])->columns(2)
                            ])
                        ]),

                        Forms\Components\Wizard\Step::make('معلومات مواقع التواصل')->schema([
                            Forms\Components\TextInput::make('social.twitter')->label('رابط تويتر')->nullable()->url()->placeholder('https://'),
                            Forms\Components\TextInput::make('social.face')->label('رابط فيسبوك')->nullable()->url()->placeholder('https://'),
                            Forms\Components\TextInput::make('social.instagram')->label('رابط إنستغرام')->nullable()->url()->placeholder('https://'),
                            Forms\Components\TextInput::make('social.youtube')->label('رابط يوتيوب')->nullable()->url()->placeholder('https://'),
                            Forms\Components\TextInput::make('social.linkedin')->label('رابط لينكدن')->nullable()->url()->placeholder('https://'),
                            Forms\Components\TextInput::make('social.telegram')->label('رابط تلغرام')->nullable()->url()->placeholder('https://'),
                        ]),

                        Forms\Components\Wizard\Step::make('خدمة التوصيل')->schema([
                            Forms\Components\Toggle::make('delivery_service')->label('خدمة التوصيل في الدردشة'),
                            Forms\Components\TextInput::make('msg_delivery')->label('رسالة متحركة'),
                            Forms\Components\Select::make('delivery_id')
                                ->relationship('delivery','name')->preload()
                               /* ->options(User::pluck('name','id'))*/->searchable()->label('مسؤول التوصيل'),
                            Forms\Components\Select::make('support_id')
                                ->relationship('support','name')->preload()
                                /*->options(User::pluck('name','id'))*/->searchable()->label('بريد مسؤول الدعم'),
                            Forms\Components\Textarea::make('msg_chat')->label('رسالة دخول مسؤول الدعم'),
                        ]),

                        Forms\Components\Wizard\Step::make('خيارات تسجيل الدخول / أسعار الصرف')->schema([
                            Forms\Components\Toggle::make('available_country')->label('تفعيل التسجيل من جميع البلدان'),
                            Forms\Components\Toggle::make('available_any_email')->label('تفعيل التسجيل من أي إيميل'),
                            Forms\Components\Toggle::make('auto_update_exchange')->label('تحديث سعر الصرف تلقائيا'),
                            Forms\Components\Toggle::make('active_points')->label('تفعيل نقاط التسويق بالعمولة'),
                            Forms\Components\TextInput::make('dollar_value')->label('سعر الدولار بالتركي')->nullable()->numeric(),
                            Forms\Components\TextInput::make('dollar_syr')->label('سعر الدولار بالسوري')->nullable()->numeric(),
                            Forms\Components\TextInput::make('point_value')->label('سعر النقطة بالدولار')->nullable()->numeric(),
                            Forms\Components\TextInput::make('num_point_for_register')->label('عدد النقاط لكل تسجيل')->nullable()->numeric(),
                            Forms\Components\TextInput::make('less_amount_point_pull')->label('أقل قيمة لسحب النقاط')->nullable()->numeric(),
                        ]),

                        Forms\Components\Wizard\Step::make('من نحن')->schema([
                            Forms\Components\RichEditor::make('about')->nullable()->label('عن التطبيق'),
                        ]),

                        Forms\Components\Wizard\Step::make('سياسة الخصوصية')->schema([
                            Forms\Components\RichEditor::make('privacy')->nullable()->label('سياسة الخصوصية'),
                        ]),

                        Forms\Components\Wizard\Step::make('البث الحي')->schema([
                            Forms\Components\Toggle::make('active_live')->label('تفعيل وضع البث')->reactive(),
                            Forms\Components\TextInput::make('live_id')->label('ID البث ')->required(fn($get) => $get('is_live'))
                        ]),

                    ])->skippable(),


                ])
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('social.name')->label('اسم الموقع'),
                Tables\Columns\TextColumn::make('social.email')->label('بريد الموقع'),
                Tables\Columns\TextColumn::make('social.phone')->label('هاتف الموقع'),
                Tables\Columns\TextColumn::make('email_delivery')->label('مسؤول التوصيل'),
                Tables\Columns\TextColumn::make('email_support')->label('مسؤول الدعم الفني'),
                Tables\Columns\TextColumn::make('dollar_value')->label('سعر الدولار'),
                Tables\Columns\TextColumn::make('active_live')->label('حالة البث'),
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
