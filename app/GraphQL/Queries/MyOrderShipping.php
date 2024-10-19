<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Order;

final class MyOrderShipping
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
      $orders=Order::where('user_id',auth()->id())->latest();
      return $orders;
    }
}
