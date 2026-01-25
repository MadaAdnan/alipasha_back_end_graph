<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Order;

final class MyOrders
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        return Order::where('user_id', auth()->id())->latest();
    }
}
