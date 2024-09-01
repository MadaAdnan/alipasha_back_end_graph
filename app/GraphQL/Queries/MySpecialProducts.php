<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\LevelProductEnum;

final class MySpecialProducts
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
     return  \App\Models\Product::where(['user_id'=>auth()->id(),'level' => LevelProductEnum::SPECIAL->value])->get();
    }
}
