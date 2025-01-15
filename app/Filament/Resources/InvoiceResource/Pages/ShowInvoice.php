<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Enums\CommunityTypeEnum;
use App\Enums\OrderStatusEnum;
use App\Filament\Resources\CommunityResource;
use App\Filament\Resources\InvoiceResource;
use App\Filament\Resources\UserResource;
use App\Models\Community;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Filament\Actions;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ShowInvoice extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getTableQuery(): ?Builder
    {

        return parent::getTableQuery()->latest(); // TODO: Change the autogenerated stub
    }

    public function table(Table $table): Table
    {
        return $table->columns([

            Panel::make([
                Split::make([
                    TextColumn::make('id'),
                    TextColumn::make('status')->formatStateUsing(fn($state)=>OrderStatusEnum::tryFrom($state)?->getLabel())->color(fn($state)=>OrderStatusEnum::tryFrom($state)?->getColor()),
                    TextColumn::make('created_at')->date('y-m-d'),
                ]),
                View::make('invoice.table.seller'),
//                View::make('invoice.table.user'),
                View::make('invoice.table.items'),
            ])/*->collapsible()->collapsed(fn($record)=>$record->status!='pending')*/

        ])
            ->actions([
                ActionGroup::make([
                   Action::make('take')->requiresConfirmation()->action(fn($record) => $record->update(['status' => OrderStatusEnum::AWAY->value]))->label('تأكيد إستلام البضاعة من التاجر')->visible(fn($record) => $record->status == OrderStatusEnum::AGREE->value),
                    Action::make('agree')->requiresConfirmation()->action(fn($record) => $record->update(['status' => OrderStatusEnum::AGREE->value]))->label('تأكيد موافقة التاجر')->visible(fn($record) => $record->status == OrderStatusEnum::PENDING->value),
                    Action::make('complete')->requiresConfirmation()->action(fn($record) => $record->update(['status' => OrderStatusEnum::COMPLETE->value]))->label('تأكيد تسليم الطلب للزبون')->visible(fn($record) => $record->status == OrderStatusEnum::AWAY->value),
                    Action::make('cancel')->requiresConfirmation()->action(fn($record) => $record->update(['status' => OrderStatusEnum::CANCELED->value]))->label('تأكيد إلغاء الطلب')->visible(fn($record) => $record->status != OrderStatusEnum::COMPLETE->value && $record->status != OrderStatusEnum::CANCELED->value),
                   Action::make('send_msg_chat')->form([
                        Radio::make('user_id')->options([
                            'seller'=>'البائع',
                            'user'=>'المشتري',
                        ])->label('اختر من تراسل')->required()->default('seller'),
                        Textarea::make('msg')->label('الرسالة')->required(),
                    ])
                        ->action(function($record,$data){
                            \DB::beginTransaction();
                            try {
                                $userId=$record->seller_id;
                                if($data['user_id']=='user'){
                                    $userId=$record->user_id;
                                }
                                $user=User::find($userId);
                                $community=Community::where('type',CommunityTypeEnum::CHAT->value)->whereHas('users',fn($query)=>$query->whereIn('users.id',[auth()->id(),$userId]))->first();
                                if($community==null){
                                    $community= Community::create([
                                        'name'=>auth()->user()->name.' - '.$user->name,
                                        'manager_id'=>auth()->id(),
                                        'type'=>CommunityTypeEnum::CHAT->value,
                                        'last_update'=>now(),
                                        'is_global'=>false,
                                    ]);
                                    $community->users()->sync([auth()->id(),$userId]);
                                }
                                Message::create([
                                    'community_id'=>$community->id,
                                    'user_id'=>auth()->id(),
                                    'body'=>$data['msg'],
                                    'type'=>'text',
                                ]);
                                \DB::commit();
                                Notification::make('success')->title('نجاح العملية')->body('تم إرسال الرسالة بنجاح')->success()->send();

                            }catch (\Exception|\Error $e){
                                \DB::rollBack();
                                Notification::make('error')->title('فشل العملية')->body($e->getMessage())->danger()->send();

                            }
                        })->label('إرسال رسالة')->icon('fas-envelope'),
                    Action::make('join')->url(function($record){
                        $community=Community::where('type',CommunityTypeEnum::CHAT->value)->whereHas('users',fn($query)=>$query->where('id',$record->seller_id)->where('users.id',$record->user_id))->first();
                        if($community){
                            return CommunityResource::getUrl('edit',['record'=>$community->id]);
                        }
                    })->label('دخول للمحادثة')
                ])
            ]); // TODO: Change the autogenerated stub
    }
}
