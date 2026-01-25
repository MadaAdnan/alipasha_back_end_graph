<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Coupon;

final class MyCoupons
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        return Coupon::where('user_id', auth()->id())
            ->latest();
    }
}
