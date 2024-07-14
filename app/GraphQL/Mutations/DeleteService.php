<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\GraphQL\Queries\Product;

final class DeleteService
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $serviceId = $args['id'];
        $userId = auth()->id();

        $service = \App\Models\Product::service()->where('user_id', $userId)->find($serviceId);
        if (!$service) {
            throw new \Exception('الإعلان غيرموجود');
        }
        $service->delete();
        return $service;
    }
}
