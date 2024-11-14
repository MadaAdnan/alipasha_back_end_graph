<?php

namespace App\Filament\Resources;

use App\Enums\CategoryTypeEnum;
use App\Filament\Resources\CommentResource\Pages;
use App\Filament\Resources\CommentResource\RelationManagers;
use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'fas-comments';
    protected static ?string $navigationGroup='التعليقات';
    protected static ?string $label='التعليقات';
    protected static ?string $navigationLabel='التعليقات';
    protected static ?string $pluralLabel='التعليقات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')->relationship('product','name',fn($query)=>$query->whereIn('type',['product','news'])->select('id','expert'))->label('المنتج')->required(),
                Forms\Components\Textarea::make('comment')->label('التعليق')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('المستخدم')->searchable()
                ->url(fn($record)=>$record->user!=null?UserResource::getUrl('edit',['record'=>$record->user->id]):null,true),
                Tables\Columns\TextColumn::make('product.id')->label('معرف المنشور')->searchable()
                    ->url(function($record){
                        switch ($record->product){
                            case CategoryTypeEnum::NEWS->value:
                              return   NewsResource::getUrl('edit',['record'=>$record->product->id]);

                            case CategoryTypeEnum::PRODUCT->value:
                                return   ProductResource::getUrl('edit',['record'=>$record->product->id]);

                        }
                    },true)->description(fn($record)=>\Str::words("{$record->product?->expert}",7)),
                Tables\Columns\TextColumn::make('comment')->label('التعليق'),
                Tables\Columns\TextColumn::make('created_at')->date('Y-m-d')->label('التاريخ'),

            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}
