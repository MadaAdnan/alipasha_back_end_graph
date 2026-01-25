<?php
namespace App\Imports;

use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;

class CityImport
{
    public function import($filePath)
    {
        $collection = (new FastExcel)->import($filePath);

        foreach ($collection as $key=>$row) {
            if($key==0){
                continue;
            }
           // dd($row);
            // افترض أن لديك جدول يسمى `your_table` وأن العمود الأول هو `column1` والعمود الثاني هو `column2`
            DB::table('cities')->insert([
                'id' => $row['Column1'],
                'name' => $row['Column2'],
                'sortable' => $row['Column3'],
                'is_main' => $row['Column4'],
                'city_id' => $row['Column5'],
                'is_delivery' => $row['Column6'],
                'is_active' => $row['Column7'],
                // أضف المزيد من الأعمدة كما هو مطلوب
            ]);
        }
    }
}
