<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Interaction;
use App\Models\Like;
use App\Models\Product;

final  class AddLike
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
       $productId=$args['product_id'];
       $product=Product::find($productId);
       if(Like::where(['product_id'=>$productId,'user_id' => auth()->id()])->exists()){
           Like::where(['product_id'=>$productId,'user_id' => auth()->id()])->delete();
       }else{
           Like::create([
               'user_id'=>auth()->id(),
               'product_id'=>$productId,
           ]);
           try{
               Interaction::updateOrCreate([
                   'user_id'=>auth()->id(),
                   'category_id'=>$product->category_id,

               ],[
                   'visited'=> \DB::raw('visited + 1'),
               ]);
           }catch(\Exception | \Error $e){
               info('ERROR:'.$e->getMessage());
           }
       }

        return $product;
    }
}
