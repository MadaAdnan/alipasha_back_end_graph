<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Product;

final class ChangeAvilable
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $id = $args['id'];
        $product = Product::where(['id' => $id, 'user_id' => auth()->id()])->first();
        if (!$product) {
            throw new \Exception('لم يتم العثور على المنتج');
        }
        $product->update(['is_available' => !$product->is_available]);
        return $product;
    }
}
