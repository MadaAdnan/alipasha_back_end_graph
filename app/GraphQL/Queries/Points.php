<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Balance;
use App\Models\Point;

final class Points
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        return Point::where('user_id', auth()->id())->latest();
    }
}
