<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;

final class UpdateUser
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        /**
         * @var $user User
         */
        $user = auth()->user();

        $is_exists_email = User::where('email', $data['email'])->whereNot('id', $user->id)->count();
        if ($is_exists_email > 0) {
            throw new \Exception('البريد مستخدم من قبل');
        }
        $user->update([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'password' => $data['password'] ?? $user->password,
            'phone' => $data['phone'] ?? null,
            'city_id' => $data['city_id'] ?? null,
        ]);

        if (isset($data['image']) && $data['image'] != null) {
            $user->clearMediaCollection('image');
            $user->addMedia($data['image'])->toMediaCollection('image');
        }

        return $user;
    }
}
