<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\CommunityTypeEnum;
use App\Models\Community;

final class CreateChannel
{
    /**
     * @param null $_
     * @param array{} $args
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
            'type' => CommunityTypeEnum::CHANNEL->value,
            'name' => $args['name'],
            'manager_id' => $userId,
            'last_update' => now(),
            'url' => $url,
        ]);
        if(isset($args['image']) && $args['image']!=null){
            $community->addMedia($args['image'])->toMediaCollection('image');
        }
        $community->users()->sync([$userId]);
          return [
        'community'=>$community,
        'user'=>auth()->user()
    ];;
    }
}
