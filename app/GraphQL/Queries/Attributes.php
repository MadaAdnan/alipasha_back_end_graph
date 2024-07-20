<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\AttributeTypeEnum;
use App\Models\Attribute;

final class Attributes
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        return Attribute::where('category_id',$args['category_id'])->where('type',AttributeTypeEnum::LIMIT->value)->with('attributes')->get();
    }
}
