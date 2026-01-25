<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;

final class UpdateAvatar
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        if (isset($data['image']) && $data['image'] != null) {
            /**
             * @var $user User
             */
            $user = auth()->user();
            $user->clearMediaCollection('image');
            $user->addMedia($data['image'])->toMediaCollection('image');
        }
    }
}
