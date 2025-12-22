<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusEnum;
use App\Filament\Resources\IdentityResource\Pages;
use App\Filament\Resources\IdentityResource\RelationManagers;
use App\Models\Identity;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IdentityResource extends Resource
{
    protected static ?string $model = Identity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'التوثيق';
    protected static ?string $modelLabel = 'التوثيق';
    protected static ?string $navigationLabel = 'طلبات التوثيق';
    protected static ?string $pluralLabel = 'طلبات التوثيق';
    protected static ?int $navigationSort = -13;
    protected static ?string $navigationGroup = 'المستخدمين';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('المستخدم')
                    ->searchable()
                    ->options(fn ($state) =>
                    $state
                        ? User::where('id', $state)->pluck('name', 'id')
                        : []
                    )
                    ->getSearchResultsUsing(fn (string $search) =>
                    User::query()
                        ->where('name', 'like', "%{$search}%")
                        ->limit(10)
                        ->pluck('name', 'id')
                    )
                    ->getOptionLabelUsing(fn ($value): ?string =>
                    User::find($value)?->name
                    ),
                Forms\Components\SpatieMediaLibraryFileUpload::make('front')->collection('front')->conversion('webp')->label('الوجه الأمامي')->required()->openable(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('back')->collection('back')->conversion('webp')->label('الوجه الخلفي')->required()->openable(),
                Forms\Components\Select::make('status')->options([
                    OrderStatusEnum::PENDING->value=>OrderStatusEnum::PENDING->getLabel(),
                    OrderStatusEnum::COMPLETE->value=>OrderStatusEnum::COMPLETE->getLabel(),
                    OrderStatusEnum::CANCELED->value=>OrderStatusEnum::CANCELED->getLabel(),
                ])->default(OrderStatusEnum::PENDING->value)->required()->label('الحالة')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#'),
                Tables\Columns\TextColumn::make('user.name')->label('المستخدم'),
                Tables\Columns\TextColumn::make('status')->formatStateUsing(fn($state)=>OrderStatusEnum::tryFrom($state)->getLabel())->label('الحالة'),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('front')->collection('front')->conversion('web')->url( fn($record)=>$record->getFirstMediaUrl('front','web'),true),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('back')->collection('back')->conversion('web')->url( fn($record)=>$record->getFirstMediaUrl('back','web'),true),
                Tables\Columns\TextColumn::make('id')->label('#'),
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
            'index' => Pages\ListIdentities::route('/'),
            'create' => Pages\CreateIdentity::route('/create'),
            'edit' => Pages\EditIdentity::route('/{record}/edit'),
        ];
    }
}
