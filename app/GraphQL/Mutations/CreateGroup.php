<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\CommunityTypeEnum;
use App\Models\Community;

final class CreateGroup
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $userId = auth()->id();

        $url = \Str::random(6);
        while (true) {

            $isUrl = Community::where('url', $url)->exists();
            if (!$isUrl) {
                break;
            }
            $url = \Str::random(6);

        }
        $community = Community::create([
            'type' => CommunityTypeEnum::GROUP->value,
            'name' => $args['name'],
            'manager_id' => $userId,
            'last_update' => now(),
            'url' => $url,
        ]);
        $community->users()->sync([$userId]);
        return $community;
    }
}
