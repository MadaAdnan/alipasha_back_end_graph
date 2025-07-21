<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('city.name'),
            ExportColumn::make('area.name'),
            ExportColumn::make('name'),
            ExportColumn::make('phone'),
            ExportColumn::make('phone_code'),
            ExportColumn::make('email'),
            ExportColumn::make('email_verified_at'),
            ExportColumn::make('is_active'),
            ExportColumn::make('level'),
            ExportColumn::make('affiliate'),
            ExportColumn::make('send_at'),
            ExportColumn::make('code_verified'),
            ExportColumn::make('is_receive_notification'),
            ExportColumn::make('notify_date'),
            ExportColumn::make('is_seller'),
            ExportColumn::make('count_channel'),
            ExportColumn::make('can_create_channel'),
            ExportColumn::make('count_group'),
            ExportColumn::make('can_create_group'),
            ExportColumn::make('longitude'),
            ExportColumn::make('latitude'),
            ExportColumn::make('seller_name'),
            ExportColumn::make('address'),
            ExportColumn::make('info'),
            ExportColumn::make('is_active_seller'),
            ExportColumn::make('level_seller'),
            ExportColumn::make('is_default_active'),
            ExportColumn::make('is_restaurant'),
            ExportColumn::make('is_special'),
            ExportColumn::make('open_time'),
            ExportColumn::make('close_time'),
            ExportColumn::make('is_delivery'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('user.name'),
            ExportColumn::make('product_count'),
            ExportColumn::make('is_verified'),
            ExportColumn::make('verified_account_date'),
            ExportColumn::make('id_color'),
            ExportColumn::make('social'),
            ExportColumn::make('category.name'),
            ExportColumn::make('country_code'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your user export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
