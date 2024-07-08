<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

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
        $proudct = \App\Models\Product::findOrFail($args['id']);
        ProductView::updateOrCreate(['view_at' => Carbon::today(), 'product_id' => $proudct->id], // الشروط التي يجب أن تتطابق
            ['count' => \DB::raw('count + 1')]);
        return $proudct;
    }
}
