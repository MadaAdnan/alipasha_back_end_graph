<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
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
        $product->update([
            'level' => $args['level']
        ]);
        return $product;
    }
}
