<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Helpers\StrHelper;
use App\Mail\RegisteredEmail;

final class ResendEmailVerify
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $user = auth()->user();
        $user->update(['code_verified' => StrHelper::generateDigits(6)]);
        \Mail::to($user)->send(new RegisteredEmail($user));
        return $user;
    }
}
