<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Advice;

final class MyAdvice
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        return Advice::where('user_id', auth()->id())->get();
    }
}
