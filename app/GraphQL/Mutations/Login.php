<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

final class Login
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $request = request();
        $throttleKey = 'login:'.$request->ip().'|'.$args['email'];

        // Check if too many login attempts
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => ["فمت بمحاولات عديدة إنتظر $seconds ثواني."]
            ]);
        }

        $user = User::where('email', $args['email'])->first();
        if (!$user || !\Hash::check($args['password'], $user->password)) {
            // Increment the login attempts
            RateLimiter::hit($throttleKey, 60);

            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => ['البريد الإلكتروني أو كلمة المرور غير صحيحة']
            ]);
        }

        // Clear the rate limiter on successful login
        RateLimiter::clear($throttleKey);

        if (isset($args['device_token']) && !empty($args['device_token'])) {
            $user->update([
                'device_token' => $args['device_token'],
            ]);
        }
        $token = $user->createToken('token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
