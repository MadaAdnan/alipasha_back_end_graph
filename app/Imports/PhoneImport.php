<?php

namespace App\Imports;


use App\Models\User;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class PhoneImport
{
    public function import($filePath)
    {

        $chunkSize = 30; // حجم الدفعة
        $collection = (new FastExcel)->import($filePath)->except(0);

        $users = array_chunk($collection->toArray(), $chunkSize);

        foreach ($users as $key1 => $chunk) {

            $data = [];


            foreach ($chunk as $key => $row) {
                User::where('id', $row['Column1'])->whereNull('phone')->update(['phone' => $row['Column2']]);

            }

        }

    }


}
