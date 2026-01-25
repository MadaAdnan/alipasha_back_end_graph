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

        $communities = Community::whereNot('type','live')->whereHas('messages')->whereHas('allUsers', function ($query) {
            $query->where('users.id', Auth::id());  // جلب المجتمعات التي يشارك فيها المستخدم الحالي
        })->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");  // البحث عن المجتمعات بناءً على الاسم
            })
            ->latest('last_update');
        return $communities;
    }
}
