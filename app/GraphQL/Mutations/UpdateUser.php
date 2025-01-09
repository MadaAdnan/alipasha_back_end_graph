<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
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
            throw new GraphQLExceptionHandler('البريد مستخدم من قبل');
        }
        $phone= $data['phone'];
        if($phone!=null && \Str::startsWith($phone,'+')){
             $phone=\Str::substr($phone,1,\Str::length($phone)-1);
        }elseif($phone!=null && \Str::startsWith($phone,'00')){
            $phone=\Str::substr($phone,2,\Str::length($phone)-1);
        }
        $input = [
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,

            'phone' => $phone ?? $user->phone,
            'city_id' => $data['city_id'] ?? $user->city_id,
            'seller_name' => $data['seller_name'] ?? $user->seller_name,
            'address' => $data['address'] ?? $user->address,
            'is_delivery' => $data['is_delivery'] ?? $user->is_delivery,
            'close_time' => $data['close_time'] ?? $user->close_time,
            'open_time' => $data['open_time'] ?? $user->open_time,
            'info' => $data['info'] ?? $user->info,
            'device_token' => $data['device_token'] ?? $user->device_token,
            'longitude' => $data['longitude'] ?? $user->longitude,
            'latitude' => $data['latitude'] ?? $user->latitude,
            'id_color' => $data['colorId'] ?? $user->id_color,
            'social' => $data['social'] ?? $user->social,
        ];
       /* if (isset($data['password']) && $data['password'] !== null && !empty(trim($data['password']))) {
            $input['password'] = bcrypt($data['password']);
        }*/
        $user->update($input);

        if (isset($data['image']) && $data['image'] != null) {
            try {
                $user->clearMediaCollection('image');
                $user->addMedia($data['image'])->toMediaCollection('image');
            } catch (\Exception | \Error $e) {
                \Log::error("Error Upload Image {$e->getMessage()}");
            }
        }

        if (isset($data['logo']) && $data['logo'] != null) {
            try {
                $user->clearMediaCollection('logo');
                $user->addMedia($data['logo'])->toMediaCollection('logo');
            } catch (\Exception | \Error $e) {
                \Log::error("Error Upload LOGO {$e->getMessage()}");
            }

        }

        return $user;
    }
}
