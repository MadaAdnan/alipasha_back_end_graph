<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Jobs\SendGlobalFirebaseNotificationJob;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Filament\Notifications\Notification;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('send_global_notification')
                ->label('إشعار فير بيسس جماعي')
                ->form([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->label('عنوان الإشعار'),
                    Forms\Components\Textarea::make('body')
                        ->required()
                        ->label('الإشعار')
                        ->rows(4),
                ])
                ->action(function (array $data) {
                    \Artisan::call('optimize:clear');
                    \Artisan::call('cache:clear');
                    \Artisan::call('config:clear');
                    // Dispatch the global notification job
                    SendGlobalFirebaseNotificationJob::dispatch($data['title'], $data['body']);

                    Notification::make()
                        ->title('تم وضع الإشعار في قائمة الإنتظار للمعالجة')
                        ->success()
                        ->send();
                })
        ];
    }

    public function getTabs(): array
    {
        return [
            'الكل'=>Tab::make('all')->query(fn($query)=>$query)->label('الكل'),
            'محظور'=>Tab::make('block')->query(fn($query)=>$query->where('is_active',0))->label('محظور'),
            'غير مؤكد'=>Tab::make('not_verified')->query(fn($query)=>$query->whereNull('email_verified_at'))->label('غير مؤكد'),
            'متاجر'=>Tab::make('is_seller')->query(fn($query)=>$query->where('is_seller',1))->label('متاجر'),
        ];
    }
}
