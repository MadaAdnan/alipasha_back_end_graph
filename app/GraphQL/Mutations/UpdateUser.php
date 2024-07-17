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
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'password' => $data['password'] ?? $user->password,
            'phone' => $data['phone'] ?? $user->phone,
            'city_id' => $data['city_id'] ?? $user->city_id,
            'seller_name'=>$data['seller_name']??$user->seller_name,
            'address'=>$data['address']??$user->address,
            'is_delivery'=>$data['is_delivery']??$user->is_delivery,
            'close_time'=>$data['close_time']??$user->close_time,
            'open_time'=>$data['open_time']??$user->open_time,
            'info'=>$data['info']??$user->info,
            'device_token'=>$data['device_token']??$user->device_token,
            'longitude'=>$data['longitude']??$user->longitude,
            'latitude'=>$data['latitude']??$user->latitude,
        ]);

        if (isset($data['image']) && $data['image'] != null) {
            try {
            $user->clearMediaCollection('image');
            $user->addMedia($data['image'])->toMediaCollection('image');
             }catch (\Exception |\Error $e){}
        }

        if (isset($data['logo']) && $data['logo'] != null) {
            try {
                $user->clearMediaCollection('logo');
                $user->addMedia($data['logo'])->toMediaCollection('image');
            }catch (\Exception |\Error $e){}

        }

        return $user;
    }
}
