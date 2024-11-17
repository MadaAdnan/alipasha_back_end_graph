<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Invoice;

final  class MyInvoice
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
        return Invoice::where('user_id',auth()->id())->latest();
    }
}
