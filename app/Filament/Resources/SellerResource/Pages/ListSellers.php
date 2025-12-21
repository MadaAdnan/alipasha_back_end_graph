<?php

namespace App\Filament\Resources\SellerResource\Pages;

use App\Filament\Resources\SellerResource;
use App\Models\Product;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSellers extends ListRecords
{
    protected static string $resource = SellerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        return User::seller()
            ->select('users.*')
            ->leftJoin('products', function($join) {
                $join->on('products.user_id', '=', 'users.id')
                   ; // إذا أردت فقط المنتجات النشطة
            })
            ->leftJoin('cities', 'users.city_id', '=', 'cities.id')
            ->leftJoin('cities as areas', 'users.area_id', '=', 'areas.id')
            ->leftJoin('categories', 'users.category_id', '=', 'categories.id')
            ->groupBy('users.id') // مهم عند استخدام aggregate مثل MAX
            ->selectRaw('MAX(products.created_at) as last_product_date')
            ->withCount(['products', 'followers'])
            ->orderByDesc('last_product_date');
    }
}
