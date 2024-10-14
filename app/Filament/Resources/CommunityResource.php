<?php

namespace App\Filament\Resources;

use App\Enums\CommunityTypeEnum;
use App\Filament\Resources\CommunityResource\Pages;
use App\Filament\Resources\CommunityResource\RelationManagers;
use App\Models\Community;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommunityResource extends Resource
{
    protected static ?string $model = Community::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المجتمعات')->schema([
                    Forms\Components\Radio::make('type')->options([
                        CommunityTypeEnum::CHAT->value=>CommunityTypeEnum::CHAT->getLabel(),
                        CommunityTypeEnum::GROUP->value=>CommunityTypeEnum::GROUP->getLabel(),
                        CommunityTypeEnum::CHANNEL->value=>CommunityTypeEnum::CHANNEL->getLabel(),
                    ])->default(CommunityTypeEnum::CHAT->value)->live()->required()->label('النوع'),
                    Forms\Components\TextInput::make('name')->label('الاسم')->required(),
                    Forms\Components\Select::make('manager_id')->options(User::pluck('name','id'))->required()->searchable()->label('المدير الرئيسي'),
                    Forms\Components\Toggle::make('is_global')->visible(fn($get)=>$get('type')!==CommunityTypeEnum::CHAT->value)->hint('سيتم إضافة جميع المستخدمين عند تفعيل الخيار')->label('عامة')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->searchable(),
                Tables\Columns\TextColumn::make('name')->label('name')->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(fn($state)=>CommunityTypeEnum::tryFrom($state)?->getLabel())
                    ->icon(fn($state)=>CommunityTypeEnum::tryFrom($state)?->getIcon())
                    ->color(fn($state)=>CommunityTypeEnum::tryFrom($state)?->getColor())
                    ->label('name')->searchable(),
                Tables\Columns\TextColumn::make('users_count')->label('عدد الأعضاء')->sortable(),
                Tables\Columns\TextColumn::make('last_update')->since()->label('آخر تعديل')->sortable(),
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
            'index' => Pages\ListCommunities::route('/'),
            'create' => Pages\CreateCommunity::route('/create'),
            'edit' => Pages\EditCommunity::route('/{record}/edit'),
        ];
    }
}
