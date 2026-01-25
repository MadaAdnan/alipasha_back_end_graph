<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;

final class CreateSeller
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        /**
         * @var $user User
         */
        $user = auth()->user();
        $sellerName = $user->seller_name;
        $data = $args['input'];
        $user->update([
            'seller_name' => $data['seller_name'],
            'is_seller' => empty($sellerName) ? true : $user->is_seller,
            'info' => $data['info'] ?? null,
            'is_delivery' => $data['is_delivery'] ?? false,
            'is_active_seller' => $data['is_active_seller'] ?? true,
            'open_time' => $data['open_time'] ?? null,
            'close_time' => $data['close_time'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        if (isset($data['image']) && $data['image'] != null) {
            $user->clearMediaCollection('logo');
            $user->addMedia($data['image'])->toMediaCollection('logo');
        }
        return $user;
    }
}
