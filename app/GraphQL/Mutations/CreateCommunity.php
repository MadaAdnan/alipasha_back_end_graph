<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Community;

final class CreateCommunity
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $userId = $args['userId'];
        $sellerId = $args['sellerId'];

        $community = Community::where(['user_id' => $userId, 'seller_id' => $sellerId])
            ->orWhere(fn($query) => $query->where(['seller_id' => $userId, 'user_id' => $sellerId]))->first();
        if (!$community) {
            $community = Community::create([
                'seller_id' => $sellerId,
                'user_id' => $userId,
                'last_change' => now(),
            ]);
        }
        return $community;
    }
}
