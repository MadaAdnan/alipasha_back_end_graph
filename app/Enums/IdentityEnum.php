<?php

namespace App\Enums;

enum IdentityEnum:string
{
    case PENDING = 'pending';
    case COMPLETE = 'complete';
    case CANCELE = 'cancel';
    case IDENTITY = 'identity';
    case PASSPORT = 'passport';
    case RECORD = 'record';

    public function getLabel()
    {
        return match ($this) {
            self::PENDING => 'بانتظار المراجعة',
            self::COMPLETE => 'موثق',
            self::CANCELE => 'مرفوض',
            self::IDENTITY => 'هوية',
            self::PASSPORT => 'جواز سفر',
            self::RECORD => 'سجل تجاري',
            default  => 'غير معروف',

        };
    }

}
