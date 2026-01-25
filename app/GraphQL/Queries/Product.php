<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\CategoryTypeEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Interaction;
use App\Models\ProductView;
use Carbon\Carbon;

final class Product
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $product = \App\Models\Product::findOrFail($args['id']);
        ProductView::updateOrCreate(['view_at' => Carbon::today(), 'product_id' => $product->id], // الشروط التي يجب أن تتطابق
            ['count' => \DB::raw('count + 1')]);

        $products= \App\Models\Product::query()->where('active', ProductActiveEnum::ACTIVE->value)
            ->where('category_id',$product->category_id)
            ->where('sub1_id',$product->sub1_id)->inRandomOrder()->latest()->take(6)->get();

        if(auth()->check() && $product->type!=CategoryTypeEnum::SERVICE->value){
            try{
                $inter=  Interaction::updateOrCreate([
                    'user_id'=>auth()->id(),
                    'category_id'=>$product->category_id,

                ],[
                    'visited'=> \DB::raw('visited + 1'),
                ]);
            }catch(\Exception | \Error $e){
                info('ERROR:'.$e->getMessage());
            }


        }



        return [
            "product"=>$product,
            "products"=>$products
        ];
    }
}
