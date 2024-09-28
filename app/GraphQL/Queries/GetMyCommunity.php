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
        $communities = Community::whereHas('users', function ($query) {
            $query->where('users.id', Auth::id());
        })
            ->with(['users' => function ($query) {
                $query->where('users.id', '!=', Auth::id())
                    ->limit(3);
            }])
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->latest('last_update');
        return $communities;
    }
}
