<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

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
        return Product::find($args['productId']);
    }
}
