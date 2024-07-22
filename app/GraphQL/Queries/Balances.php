<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Balance;

final class Balances
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        return Balance::where('user_id', auth()->id())->latest();
    }
}
