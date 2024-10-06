<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\CommunityTypeEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Community;
use App\Models\Message;
use App\Models\Setting;

final class CreateLiveMessage
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $setting = Setting::first();
        if ($setting->active_live) {
            $community=Community::firstOrCreate([
                'name'=>$setting->live_id,
                'type' => CommunityTypeEnum::LIVE->value
            ],[
                'is_global' => true,
                'last_update' => now()
            ]);
          return  Message::create([
                'body' => $args['body'],
                'community_id' => $community->id,
                'type' => 'text',
                'user_id' => auth()->id(),
            ]);
        }
        throw new GraphQLExceptionHandler('إنتهى البث');
    }
}
