<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;

final class ChangePassword
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $password=$args['password'];
        $confirm=$args['confirm'];
        if($password!=$confirm){
         throw new GraphQLExceptionHandler('كلمة المرور غير متطابقة');
        }
        if(\Str::length($password)<8){
            throw new GraphQLExceptionHandler('كلمة المرور قصيرة');
        }
        auth()->user()->update([
           'password'=>bcrypt($password)
        ]);
        return true;
    }
}
