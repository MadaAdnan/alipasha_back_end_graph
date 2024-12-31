<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Helpers\HelpersEnum;
use App\Helpers\StrHelper;
use App\Jobs\ForgetPasswordJob;
use App\Mail\ForgetPasswordEmail;
use App\Models\User;

final  class ForgetPassword
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
        $email=$args['email'];
        $user=User::where('email',$email)->first();
        if(!$user){
            throw new GraphQLExceptionHandler("البريد المدخل غير صحيح");
        }
        $code=StrHelper::getResetPassword();
        $user->update(['reset_password'=>$code]);
       $job=new ForgetPasswordJob($user,new ForgetPasswordEmail($code));
       dispatch($job);

        return true;
    }
}
