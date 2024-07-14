<?php

namespace App\Helpers;

class HelpersEnum
{
    public static function getEmailVerified($verfied = '', $type = 'label')
    {
        $data=null;
        switch ($type) {
            case 'label':
                $data= match ($verfied) {
                    'not' => 'غير مؤكد',
                    default => 'مؤكد'
                };
                break;
            case 'icon':
                $data =match ($verfied) {
                    'not' => 'fas-circle-xmark',
                    default => 'fas-check'
                };
                break;
            case 'color':
                $data= match ($verfied) {
                    'not' => 'danger',
                    default => 'success'
                };
                break;
        }
        if($verfied==='test'){
          //  dd($data);
        }
        return $data;
    }
}
