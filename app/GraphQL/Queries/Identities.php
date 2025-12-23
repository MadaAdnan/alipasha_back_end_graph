<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\OrderStatusEnum;
use App\Models\Identity;

final  class Identities
{
    /** @param array{} $args */
    public function __invoke($_, array $args)
    {
        $identity = Identity::where('user_id', auth()->id)->where(function ($query) {
            $query->where('status', 'pending');
            $query->orWhere('status', OrderStatusEnum::COMPLETE->value);
        })->first();
        return [
            'identity' => $identity,
            'user' => auth()->user()
        ];
    }
}
