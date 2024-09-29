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
      /*  $communities = Community::whereHas('users', function ($query) {
            $query->where('users.id', Auth::id());
        })
            ->with(['users' => function ($query) {
                $query->select('users.*')
                    ->join('community_user as t', 'users.id', '=', 't.user_id')
                    ->where('users.id', '!=', Auth::id())
                    ->take(3);
            }])
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->latest('last_update');*/
        $communities = Community::whereHas('allUsers', function ($query) {
            $query->where('users.id', Auth::id());  // جلب المجتمعات التي يشارك فيها المستخدم الحالي
        })
            ->with('users')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");  // البحث عن المجتمعات بناءً على الاسم
            })
            ->latest('last_update');
        return $communities;
    }
}
