<?php

namespace App\Filament\Resources;

use App\Enums\IsActiveEnum;
use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Helpers\HelperMedia;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 23;
    protected static ?string $navigationGroup = 'الأساسي';
    protected static ?string $label = 'سؤال';
    protected static ?string $modelLabel = 'سؤال';
    protected static ?string $navigationLabel = 'الأسئلة';
    protected static ?string $pluralLabel = 'الأسئلة';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Forms\Components\Section::make('السلايدرات')->schema([
                  HelperMedia::getFileUpload(),
                  Forms\Components\TextInput::make('ask')->label('السؤال')->required(),
                  Forms\Components\RichEditor::make('answer')->label('الإجابة')->required(),
                  Forms\Components\Radio::make('is_active')->options([
                      IsActiveEnum::ACTIVE->value=>IsActiveEnum::ACTIVE->getLabel(),
                      IsActiveEnum::INACTIVE->value=>IsActiveEnum::INACTIVE->getLabel(),
                  ])->label('حالة السؤال')->default(IsActiveEnum::ACTIVE->value)->inline()
              ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
