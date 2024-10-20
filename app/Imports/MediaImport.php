<?php

namespace App\Imports;


use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class MediaImport
{
    public function import($filePath)
    {

        $chunkSize = 30; // حجم الدفعة
        $collection = (new FastExcel)->import($filePath)->except(0);

        $users = array_chunk($collection->toArray(), $chunkSize);

        foreach ($users as $key1 => $chunk) {

            $data = [];


            foreach ($chunk as $key => $row) {
                $model = $row['Column9'] == 'App\\Models\\Tender' || $row['Column9'] == 'App\\Models\\Job' || $row['Column9'] == 'App\\Models\\Product' || $row['Column9'] == 'App\\Models\\Item' ? Product::class : $row['Column9'];
                $data[] = [
                    'id' => $row['Column1'],
                    'model_id' => $row['Column2'],
                    'collection_name' => $row['Column3'],
                    'order_column' => $row['Column4'],
                    'conversions_disk' => $row['Column5'],
                    'uuid' => 'user',
                    'mime_type' => empty($row['Column7']) ? null : $row['Column7'],
                    'file_name' => $row['Column8'],
                    'model_type' => $model,
                    'disk' => $row['Column10'],
                    'size' => $row['Column11'],
                    'generated_conversions' => json_encode(["webp" => true]),
                    'custom_properties' => json_encode([]),
                    'responsive_images' => json_encode([]),
                    'manipulations' => json_encode([]),
                    'name' => $row['Column12'],
                    'created_at' => now(),
                    'updated_at' => now(),


                    // أضف المزيد من الأعمدة كما هو مطلوب
                ];
                // افترض أن لديك جدول يسمى `your_table` وأن العمود الأول هو `column1` والعمود الثاني هو `column2`

            }

            DB::table('media')->insert($data);
        }

    }


}
