<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'الصفحة';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?string $pluralModelLabel = 'الصفحات';
    protected static ?string $modelLabel = 'الصفحة';
    protected static ?string $navigationLabel = 'الصفحات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Forms\Components\Section::make('الصفحات')->schema([
                   Forms\Components\TextInput::make('title')->required()->label('اسم الصفحة'),
                   Forms\Components\TextInput::make('url')->required()->label('رابط الصفحة'),
                   Forms\Components\TextInput::make('icon')->required()->label('الأيقونة')->hint('حصراً كلاس من fontawesome.com'),
                   Forms\Components\Toggle::make('active')->label('فعال / غير فعال')
               ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('title')->label('اسم الصفحة'),
                Tables\Columns\TextColumn::make('url')->label('رابط الصفحة')->wrap()->url(function($record){
                    $url=$record->url;
                    if(!Str::startsWith($record->url,['https://','http://','//'])){
                        $url=url($record->url);
                    }
                    return $url;
                },true),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
