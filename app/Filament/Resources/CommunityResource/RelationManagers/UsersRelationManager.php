<?php

namespace App\Filament\Resources\CommunityResource\RelationManagers;

use App\Enums\CommunityTypeEnum;
use App\Models\Community;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';
protected static ?string $label='المستخدمين';
   /* public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }*/

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('البريد')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('الهاتف')->searchable(),
                Tables\Columns\TextColumn::make('seller_name')->label('المتجر')->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('attach')->form([
                    Forms\Components\Select::make('users')->relationship('users','name')->searchable()->multiple(),
                ])->action(function($data){
                    /**
                     * @var $record Community
                     */
                    $record=$this->ownerRecord;
                    if($record->type!=CommunityTypeEnum::CHAT->value){
                        $record->users()->sync($data['users']);
                        Notification::make('success')->success()->title('نجاح العملية')->body('تم إضافة المستخدمين إلى المجتمع')->send();
                    }else{
                        Notification::make('error')->danger()->title('فشل العملية')->body('لا يمكن إضافة مستخدمين إلى محادثة خاصة')->send();

                    }

                })
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
            Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
