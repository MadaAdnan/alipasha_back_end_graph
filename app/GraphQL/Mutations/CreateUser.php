<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;

final class CreateUser
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        $affiliate_id = null;
        if (isset($data['affiliate']) && $data['affiliate'] != null) {
            $affiliate_id = User::where('affiliate', $data['affiliate'])->first()?->id;

        }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => $data['phone'],
            'city_id' => $data['city_id'] ?? null,
            'device_token' => $data['device_token'] ?? null,
            'level' => 'user',
            'is_active' => true,
            'user_id' => $affiliate_id,
            'code_verified' => \Str::random(6)
        ]);
        $token = $user->createToken('User')->plainTextToken;
        if (isset($data['image']) && $data['image'] !== null) {
            $user->addMedia($data['imag'])->toMediaCollection('image');
        }
        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
