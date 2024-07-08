<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';
protected static ?string $title='التعليقات';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('comment')
                    ->required()
                    ->maxLength(255)->label('التعليق')->columnSpan(2),
                Forms\Components\Placeholder::make('user_id')->content(fn($record) => $record->user?->name)->label('المستخدم'),
                Forms\Components\Placeholder::make('created_at')->content(fn($record) => $record->created_at?->format('Y-m-d'))->label('تاريخ كتابة التعليق')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->latest())
            ->recordTitleAttribute('comment')
            ->columns([
                Tables\Columns\TextColumn::make('comment')->searchable()->label('التعليق'),
                Tables\Columns\TextColumn::make('user.name')->label('المستخدم'),
                Tables\Columns\TextColumn::make('created_at')->since()->label('أضيف منذ')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
