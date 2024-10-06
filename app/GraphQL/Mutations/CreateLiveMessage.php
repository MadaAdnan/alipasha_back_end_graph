<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
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
          return  Message::create([
                'body' => $args['body'],
                'community_id' => $args['communityId'],
                'type' => 'text',
                'user_id' => auth()->id(),
            ]);
        }
        throw new GraphQLExceptionHandler('إنتهى البث');
    }
}
