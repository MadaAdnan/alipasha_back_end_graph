<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\CommunityTypeEnum;
use App\Models\Community;
use App\Models\Setting;

final class GetLiveCommunity
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $setting = Setting::first();
        if ($setting->active_live) {
            return Community::firstOrCreate(['name' => $setting->live_id], ['is_global' => true, 'type' => CommunityTypeEnum::LIVE->value]);
        }
    }
}
