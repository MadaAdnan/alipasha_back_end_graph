<?php

namespace App\Imports;


use App\Enums\CategoryTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class ServicesImport
{
    public function import($filePath)
    {

        $chunkSize = 1000; // حجم الدفعة
        $collection = (new FastExcel)->import($filePath)->except(0);

        $users = array_chunk($collection->toArray(), $chunkSize);

        foreach ($users as $key1 => $chunk) {

            $data = [];


            foreach ($chunk as $key => $row) {
                $data[] = [
                    'city_id' => !empty($row['Column2']) ? $row['Column2'] : null,
                    'user_id' =>!empty( $row['Column3'])? $row['Column3']:null,
                    'name' => $row['Column4'],
                    'info' => $row['Column5'],
                    'expert' => $row['Column5'],
                    'active' => ($row['Column6'] === 'active') ? ProductActiveEnum::ACTIVE->value : (($row['Column6'] === 'inactive') ? ProductActiveEnum::BLOCK->value : ProductActiveEnum::PENDING->value),
                    'phone' => \Str::replace('+','',$row['Column7']),
                    'address'=>$row['Column8'],
                    'email' => !empty($row['Column9'])?$row['Column9']:null,

                    'created_at' => $row['Column10'],

                    'type' =>CategoryTypeEnum::SERVICE->value,

                ];

            }

            DB::table('products')->insert($data);
        }

    }


}
