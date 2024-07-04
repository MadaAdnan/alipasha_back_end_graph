<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Nette\Schema\ValidationException;
use function PHPUnit\Framework\throwException;

final class Login
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user=User::where('email',$args['email'])->first();
        if(!$user || !\Hash::check($args['password'],$user->password)){
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email'=>['البريد الإلكتروني أو كلمة المرور غير صحيحة']
            ]);
        }

        $token=$user->createToken('token')->plainTextToken;
        return $token;
    }
}
