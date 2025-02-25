<?php

namespace App\Filament\Resources\CommunityResource\RelationManagers;

use App\Enums\CommunityTypeEnum;
use App\Filament\Resources\UserResource;
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
protected static ?string $title='المستخدمين';

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
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable()->url(fn($record)=>UserResource::getUrl('edit',['record'=>$record->id]),true),
                Tables\Columns\TextColumn::make('email')->label('البريد')->searchable()->url(fn($record)=>UserResource::getUrl('edit',['record'=>$record->id]),true),
                Tables\Columns\TextColumn::make('phone')->label('الهاتف')->searchable()->url(fn($record)=>'https://wa.me/'.$record->phone,true),
                Tables\Columns\TextColumn::make('seller_name')->label('المتجر')->searchable()->url(fn($record)=>UserResource::getUrl('edit',['record'=>$record->id]),true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('attach')->form([
                    Forms\Components\Select::make('users')
                        ->getSearchResultsUsing(fn(string $search)=>User::where('name','like',"%{$search}%")->limit(25)->pluck('name','id'))
                        ->getOptionLabelsUsing(fn (array $values): array => User::whereIn('id', $values)->pluck('name', 'id')->toArray())
                        ->searchable()->multiple(),
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

                })->label('إضافة مستخدمين')
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
