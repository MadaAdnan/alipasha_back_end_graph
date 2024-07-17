<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\User;

final class SpecialSeller
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        return User::where( 'is_special', true)->where('is_active_seller' , true)->inRandomOrder()->limit(20)->get();
    }
}
