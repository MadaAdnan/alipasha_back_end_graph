<?php
namespace App\Imports;

use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;

class ViewsImport
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
            DB::table('product_views')->insert([

                'product_id' => $row['Column1'],
                'count' => $row['Column2'],
                'view_at' => !empty($row['Column3'])?$row['Column3']:now(),
                'created_at'=>now(),
                'updated_at'=>now(),
                // أضف المزيد من الأعمدة كما هو مطلوب
            ]);
        }
    }
}

