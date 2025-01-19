<?php

namespace App\Models;

use DutchCodingCompany\FilamentSocialite\Models\Contracts\FilamentSocialiteUser as FilamentSocialiteUserContract;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;

class SocialiteUser implements FilamentSocialiteUserContract
{
    public function getUser(): Authenticatable
    {
        $user = $this->user; // Assuming $this->user is your relationship to the user model

        if (!$user) {
            // Handle the case where the user is not found
            // For example, throw an exception or create a new user
            throw new Exception('User not found.');
            // Alternatively, create a new user:
            // $user = User::create([...]);
        }

        return $user;
    }

    public static function findForProvider(string $provider, SocialiteUserContract $oauthUser): ?self
    {
        //
    }

    public static function createForProvider(
        string $provider,
        SocialiteUserContract $oauthUser,
        Authenticatable $user
    ): self {
    }
}
