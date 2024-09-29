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
       $communities=auth()->user()->communities()->with(['users'=>fn($q)=>$q->take(3)])->latest('last_update');
        return $communities;
    }
}
