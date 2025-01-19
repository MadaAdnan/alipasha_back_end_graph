<?php

namespace App\Models;

use App\Enums\LevelUserEnum;
use DutchCodingCompany\FilamentSocialite\Models\Contracts\FilamentSocialiteUser as FilamentSocialiteUserContract;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;

class SocialiteUser extends Model implements FilamentSocialiteUserContract
{
    protected $guarded = [];

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
        return self::where('provider', $provider)
            ->where('provider_id', $oauthUser->getId())
            ->first();
    }

    public static function createForProvider(
        string                $provider,
        SocialiteUserContract $oauthUser,
        Authenticatable       $user
    ): self
    {
        $user->update([
            'password' => bcrypt('fpEV.JY.R2zw7Uv'),
            'is_seller' => true,
            'seller_name' => $user->name,
            'level' => LevelUserEnum::SELLER->value,
        ]);
        $plan=Plan::where(['is_active'=>1,'price'=>0])->first();
        $user->plans()->sync($plan->id,false);
        return self::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $oauthUser->getId(),
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
