<?php

namespace App\Helpers;

class HelpersEnum
{
    public static function getEmailVerified($verfied = null, $type = 'label')
    {
        switch ($type) {
            case 'label':
                return match ($verfied) {
                    null => 'غير مؤكد',
                    default => 'مؤكد'
                };
                break;
            case 'icon':
                return match ($verfied) {
                    null => 'fas-circle-xmark',
                    default => 'fas-check'
                };
                break;
            case 'color':
                return match ($verfied) {
                    null => 'danger',
                    default => 'success'
                };
                break;
        }
    }
}
