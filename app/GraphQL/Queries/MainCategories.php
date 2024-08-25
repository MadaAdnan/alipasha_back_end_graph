<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Category;

final class MainCategories
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $type = $args['type']??null;
        return Category::
        when(!empty($type), function ($query) use ($type) {
            if ($type === 'job' || $type === 'search_job') {
                $query->where('type', 'job')->orWhere('type', 'search_job');
            } else {
                $query->where('type', $type);
            }
        })
            ->where(['is_active' => true, 'is_main' => true/*,'type' => 'product'*/])
            ->orderByRaw("FIELD(type, 'product', 'job', 'search_job','tender','service','news')")
            ->orderBy('sortable')
            ->get();
    }
}
