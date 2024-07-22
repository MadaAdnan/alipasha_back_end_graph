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
        $advices = Advice::where('user_id', auth()->id())->get();

        return [
            'advices' > $advices,
            'advice_count' => $advices->count(),
            'my_balance'=>190.5,
            'my_point'=>100,
            'my_wins'=>20.8,
            'views'=>300,
            'slider_count'=>3,
        ];
    }
}
