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
             Forms\Components\Section::make('test')->schema([
                 Forms\Components\TextInput::make('test')
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
