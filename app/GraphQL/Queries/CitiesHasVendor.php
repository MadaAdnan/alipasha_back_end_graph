<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\City;

final class CitiesHasVendor
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $type=$args['type']??'seller';
        switch ($type){
            case 'seller':
                return City::whereHas('sellers')->orderBy('sortable')->get();
            default:
                return City::whereHas('partners')->orderBy('sortable')->get();
        }

    }
}
