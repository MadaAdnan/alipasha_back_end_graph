<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Community;
use Auth;

final class GetMyCommunity
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $search = $args['search'] ?? '';
        $communities = auth()->user()?->communities()->latest('last_update');

// اجلب 3 مستخدمين فقط لكل مجتمع
        $communities->each(function($community) {
            $community->setRelation('users', $community->users()->take(3)->get());
        });
        return $communities;
    }
}
