<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Helpers\ProductsHelper;
use App\Models\Product;

final class AddSpecialProduct
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $productId = $args['id'];
        $product = Product::where('user_id', auth()->id())->find($productId);
        if (!$product) {
            throw new GraphQLExceptionHandler('المنتج غير موجود');
        }
        if($args['level']=='special' ){
            if( ProductsHelper::canAddSpecial()){
                $product->update([
                    'level' => 'special'
                ]);
            }else{
                throw new GraphQLExceptionHandler('لا يمكنك اضافة منتج خاص');
            }

        }else{
            $product->update([
                'level' => 'normal'
            ]);
        }

        return $product;
    }
}
