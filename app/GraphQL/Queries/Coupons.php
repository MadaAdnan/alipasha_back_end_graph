<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Coupon;

final class Coupons
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        return Coupon::whereNull('user_id')->groupBy('price')->get();
    }
}
