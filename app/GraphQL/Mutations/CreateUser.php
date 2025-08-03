<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Helpers\StrHelper;
use App\Models\Setting;
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
        info('Manaully create user');
        info(json_encode($data));
        $affiliate_id = null;
        $setting = Setting::first();
        if (!$setting->available_any_email) {
            $explod = explode('@', $data['email']);
            if (!\Str::contains($explod[1], 'gmail',false) && !\Str::contains($explod[1], 'hotmail',false) && !\Str::contains($explod[1], 'live',false) && !\Str::contains($explod[1], 'outlook',false)) {
                throw new GraphQLExceptionHandler(['message' => ' يمكن التسجيل  من خلال بريد GMAIL - HOTMAIL  فقط',]);
            }

        }
        if (isset($data['affiliate']) && $data['affiliate'] != null) {
            $affiliate_id = User::where('affiliate', $data['affiliate'])->first()?->id;

        }

        $phone = $data['phone'];
        if (\Str::startsWith($phone, '+')) {
            $phone = \Str::substr($phone, 1, Str::length($phone) - 1);
        } elseif (\Str::startsWith($phone, '00')) {
            $phone = \Str::substr($phone, 2, Str::length($phone) - 1);
        }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => $phone,
            'phone_code'=>$data['phone_code'],
            'city_id' => $data['city_id'] ?? null,
            'area_id' => $data['area_id'] ?? null,
            'device_token' => $data['device_token'] ?? null,
            'level' => 'user',
            'address' => $data['address'] ?? null,
            'is_active' => true,
            'user_id' => $affiliate_id,
            'code_verified' =>StrHelper::generateDigits(6),
            'is_special' => false,
            'seller_name'=>$data['name']
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

