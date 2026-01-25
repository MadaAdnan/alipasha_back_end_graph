<?php

namespace App\Imports;


use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class UserImport
{
    public function import($filePath)
    {

        $chunkSize =30; // حجم الدفعة
        $collection = (new FastExcel)->import($filePath)->except(0);

        $users=array_chunk($collection->toArray(), $chunkSize);

            foreach ( $users as $key1=> $chunk) {

                $data = [];


                foreach ($chunk as $key => $row) {

                  $data[]=[
                      'id' => $row['Column1'],
                      'name' => $row['Column2'],
                      'password' => $row['Column3'],
                      'phone' => $row['Column4'],
                      'email' => $row['Column5'],
                      'level' => 'user',
                      'city_id' =>empty( $row['Column7'])?null: $row['Column7'],
                      'seller_name' => $row['Column8'],
                      'address' => $row['Column9'],
                      'is_seller' => $row['Column10'],
                      'is_active_seller' => $row['Column10'],
                      'is_default_active' => $row['Column11'],
                      'email_verified_at' => $row['Column12'],
                      'created_at' => $row['Column13'],

                      // أضف المزيد من الأعمدة كما هو مطلوب
                  ];
                    // افترض أن لديك جدول يسمى `your_table` وأن العمود الأول هو `column1` والعمود الثاني هو `column2`

                }

                DB::table('users')->insert($data);
            }

    }


}
