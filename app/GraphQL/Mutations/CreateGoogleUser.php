<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class CreateGoogleUser
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
      /*  $affiliate_id = null;
        if (isset($data['affiliate']) && $data['affiliate'] != null) {
            $affiliate_id = User::where('affiliate', $data['affiliate'])->first()?->id;

        }*/
        $user=User::where('email',$data['email'])->first();

        if(!$user){
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'device_token' => $data['device_token'] ?? null,
                'level' => 'user',
                'is_active' => true,
                'code_verified' => \Str::random(6)
            ]);
        }else{
            $user->update(['device_token' => $data['device_token'] ?? null,]);
        }

        if(!Hash::check($data['password'],$user->password)){
            throw new GraphQLExceptionHandler('لم تقم بالتسجيل بهذا البريد من خلال google');
        }



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
