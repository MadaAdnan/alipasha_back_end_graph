<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Community;

final class GetMyCommunity
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $search = $args['search'] ?? '';
        $communities = Community::whereHas('users', fn($query) => $query->where('users.id', auth()->id()))
            ->when(!empty($search), fn($query) => $query->where('name', 'like', "%$search%"))
            ->latest('last_update');
        return $communities;
    }
}
