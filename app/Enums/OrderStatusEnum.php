<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case PENDING = 'pending';
    case AWAY = 'away';
    case COMPLETE = 'complete';
    case CANCELED = 'canceled';



    public function getLabel()
    {
        return match ($this) {
            self::PENDING => 'بإنتظار',
            self::AWAY => 'في الطريق',
            self::COMPLETE => 'تم التسليم',
            self::CANCELED => 'ملغي',

        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::PENDING => 'fas-hourglass-half',
            self::AWAY =>'fas-thumbs-up',
            self::COMPLETE =>'fas-check',
            self::CANCELED =>'fas-ban',

        };
    }

    public function getColor()
    {
        return match ($this) {
            self::PENDING => 'info',
            self::AWAY => 'gold',
            self::COMPLETE => 'success',
            self::CANCELED => 'danger',

        };
    }
}
