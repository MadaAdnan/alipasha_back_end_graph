<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\IsActiveEnum;
use App\Enums\ProductActiveEnum;

final class SearchProduct
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
      return \App\Models\Product::where('active',ProductActiveEnum::ACTIVE->value)->where('type',$args['type'])->search($args['search']);
    }
}
