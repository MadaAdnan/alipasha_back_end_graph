<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

final class VerifyEmail
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $code = $args['code'];
        if (auth()->user()->code_verified === $code) {
            auth()->user()->update(['email_verified_at' => now()]);
        }else{
            throw new \Exception('كود التفعيل غير صحيح');
        }
        return auth()->user();
    }
}
