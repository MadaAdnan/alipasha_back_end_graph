<?php

namespace App\Helpers;

use App\Models\User;

class StrHelper
{

    public static function getAfflieate()
    {

        $alfa = range('a', 'z');
        $nums = range(0, 9);
        $list = array_merge($alfa, $nums);

        $listGenerate = [];
        $code='';
        while (true) {
            $listGenerate[] = $list[rand(0, 35)];
            $listGenerate[] = $list[rand(0, 35)];
            $listGenerate[] = $list[rand(0, 35)];
            $listGenerate[] = $list[rand(0, 35)];
            $listGenerate[] = $list[rand(0, 35)];
            shuffle($listGenerate);
            $code=implode('', $listGenerate);
            $isExist = User::where('affiliate', $code)->exists();
            if (!$isExist) {
                break;
            }
        }

        return $code;
    }

    public static function generateMd5(){
        return md5('ali-pasha5'.now()->day);
    }
}
