<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';
    protected static ?string $title = 'التعليقات';
    protected static ?string $label = 'التعليقات';
    protected static ?string $pluralLabel = 'التعليقات';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('comment')
                    ->required()
                    ->maxLength(255)->label('التعليق')->columnSpan(2),
                Forms\Components\TextInput::make('user_id')->default(fn($record, $context) => $context == 'view' || $context == 'edit' ? $record->user?->name : '')->label('المستخدم')->visible(fn($context) => $context == 'view'),
                Forms\Components\Placeholder::make('created_at')->content(fn($record, $context) => $context == 'view' || $context == 'edit' ? $record->created_at?->format('Y-m-d') : '')->label('تاريخ كتابة التعليق')->visible(fn($context) => $context == 'view')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->latest())
            ->recordTitleAttribute('comment')
            ->columns([
                Tables\Columns\TextColumn::make('comment')->searchable()->label('التعليق'),
                Tables\Columns\TextColumn::make('user.name')->label('تعليق المستخدم'),
                Tables\Columns\TextColumn::make('replay.user.name')->label('رد على'),
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
                Tables\Actions\Action::make('replay')->form([
                    Forms\Components\Textarea::make('replay')->required()->maxLength(255)->label('الرد'),
                ])->action(function ($record, $data) {
                    Comment::create([
                        'comment' => $data['replay'],
                        'user_id' => auth()->id(),
                        'product_id' => $record->product_id,
                        'comment_id' => $record->id,

                    ]);
                    Notification::make('success')->title('نجاح العملية')->body('تم إضافة الرد')->success()->send();
                })->label('الرد')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
