<?php

namespace App\Filament\Resources\PlanResource\RelationManagers;

use App\Filament\Resources\UserResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable()->url(fn($record) => UserResource::getUrl('edit', ['record' => $record->id]), true),
                Tables\Columns\TextColumn::make('email')->label('البريد')->searchable()->url(fn($record) => UserResource::getUrl('edit', ['record' => $record->id]), true),
                Tables\Columns\TextColumn::make('phone')->label('الهاتف')->searchable()->url(fn($record) => 'https://wa.me/' . $record->phone, true),
                Tables\Columns\TextColumn::make('seller_name')->label('المتجر')->searchable()->url(fn($record) => UserResource::getUrl('edit', ['record' => $record->id]), true),
                Tables\Columns\TextColumn::make('expired_at')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //   Tables\Actions\CreateAction::make(),
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
