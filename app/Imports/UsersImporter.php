<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImporter implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
      $product=Product::find($row[0]);
      if($product){
          dd($product);
      }
    }
}
