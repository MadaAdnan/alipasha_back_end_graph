<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\CommunityTypeEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Community;
use App\Models\Message;
use App\Models\Setting;

final class GetLiveMessages
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $setting=Setting::first();
        if($setting->active_live){
            $community=Community::where([
                'name'=>$setting->live_id,
                'type' => CommunityTypeEnum::LIVE->value
            ])->first();
            return Message::where('community_id',$community?->id)->latest();
        }
        throw new GraphQLExceptionHandler('إنتهى البث');
    }
}
