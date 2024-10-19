<?php
namespace App\Imports;

use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;

class ColorImport
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
            DB::table('colors')->insert([
                'id' => $row['Column1'],
                'name' => $row['Column2'],
                'code' => $row['Column3'],
                // أضف المزيد من الأعمدة كما هو مطلوب
            ]);
        }
    }
}
