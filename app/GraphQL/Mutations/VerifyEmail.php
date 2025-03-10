<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Point;
use App\Models\Setting;
use App\Models\User;

final class VerifyEmail
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $code = $args['code'];

        if (auth()->user()->code_verified == $code) {
            try {
                auth()->user()->update(['email_verified_at' => now(), 'code_verified' => null]);

            } catch (\Exception $e) {
                throw new GraphQLExceptionHandler('يرجى المحاولة مرة أخرى');
            }
        } else {
            throw new GraphQLExceptionHandler('كود التفعيل غير صحيح');
        }
        return auth()->user();
    }
}
