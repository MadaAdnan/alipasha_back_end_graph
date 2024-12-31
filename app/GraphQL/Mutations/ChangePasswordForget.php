<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Helpers\StrHelper;
use App\Jobs\SendEmailJob;
use App\Mail\ResetPasswordForgetEmail;
use App\Models\User;
use Filament\Notifications\Auth\ResetPassword;

final  class ChangePasswordForget
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
        $data=$args;
        $user=User::where(['email'=>$data['email'],'reset_password' =>$data['code'] ])->first();
        if($user){
            $password=StrHelper::getResetPassword();
            $user->update(['password'=>bcrypt($password)]);
            $job=new SendEmailJob($user,new ResetPasswordForgetEmail($password));
            dispatch($job);
            return true;

        }
        return false;
    }
}
