<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Interaction;
use App\Models\Product;
use App\Models\Rate;

final class AddVote
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        Rate::updateOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $args['productId'],
        ], [
            'vote' => $args['vote'],
        ]);
        try{
            $product=Product::find($args['productId']);
            Interaction::updateOrCreate([
                'user_id'=>auth()->id(),
                'category_id'=>$product->sub1_id,

            ],[
                'visited'=> \DB::raw("visited + {$args['vote']}"),
            ]);
        }catch (\Exception | \Error $e){

        }

        return Product::find($args['productId']);
    }
}
