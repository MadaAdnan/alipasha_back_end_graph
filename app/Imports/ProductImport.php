<?php

namespace App\Imports;


use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductImport
{
    public function import($filePath)
    {

        $chunkSize = 1000; // حجم الدفعة
        $collection = (new FastExcel)->import($filePath)->except(0);

        $products = array_chunk($collection->toArray(), $chunkSize);

        foreach ($products as $key1 => $chunk) {

            $data = [];


            foreach ($chunk as $key => $row) {
                $data= [
                    'id' => $row['Column1']??'',
                    'name' => $row['Column2']??'',
                    'info' => $row['Column3']??'',
                    'expert' => $row['Column4']??'',
                    'user_id' =>!empty( $row['Column5'])? $row['Column5']:null,
                    'phone' => $row['Column6']??'',
                    'email' => !empty($row['Column7'])?$row['Column7']:null,
                    'type' => $row['Column8']??'',
                    'price' => !empty($row['Column9']) ? $row['Column9'] : 0,
                    'discount' => !empty($row['Column10']) ? $row['Column10'] : 0,
                    'active' => ($row['Column11'] === 'active') ? ProductActiveEnum::ACTIVE->value : (($row['Column11'] === 'pending') ? ProductActiveEnum::PENDING->value : ProductActiveEnum::BLOCK->value),
                    'is_available' => $row['Column12'] ?? false,
                    'is_discount' => $row['Column13'] ?? false,
                    'level' => $row['Column14'] === 'special' ? LevelProductEnum::SPECIAL->value : LevelProductEnum::NORMAL->value,
                    'url' => $row['Column15']??'',
                    'start_date' => !empty($row['Column16']) ? $row['Column16'] : null,
                    'end_date' => !empty($row['Column17']) ? $row['Column17'] : null,
                   // 'video' => $row['Column18'],
                    'code' => !empty($row['Column21']) ? $row['Column21'] : null,
                    'created_at' => $row['Column22']??'',
                    'deleted_at' => !empty($row['Column23']) ? $row['Column23'] : null,
                    'city_id' => !empty($row['Column24']) ? $row['Column24'] : null,
                    'tags'=>!empty($row['Column27']) ? $row['Column27'] : null,
                    // أضف المزيد من الأعمدة كما هو مطلوب
                ];
                // افترض أن لديك جدول يسمى `your_table` وأن العمود الأول هو `column1` والعمود الثاني هو `column2`
                DB::table('products')->updateOrInsert(['id'=>$data['id']],$data);
            }


        }

    }


}
