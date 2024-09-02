<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Filament\Resources\CouponResource;
use App\Models\Coupon;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;

class ListCoupons extends ListRecords
{
    protected static string $resource = CouponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')->form([
                TextInput::make('price')->label('سعر الكوبون')->numeric()->required(),
                TextInput::make('count')->label('عدد الكوبونات')->numeric()->required(),
            ])->action(function ($data) {
                for ($i = 0; $i < $data['count']; $i++) {
                    Coupon::create([
                        'price' => $data['price'],
                        'code' => \Str::random(6),
                        'password' => \Str::random(6),
                        'is_active' => true,
                    ]);
                }

            })->label('إضافة كوبونات')->visible(fn() => CouponResource::canCreate()),
        ];
    }
}
