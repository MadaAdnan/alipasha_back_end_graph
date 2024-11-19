<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Invoice;

final  class SellerInvoice
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
     return Invoice::where('seller_id',auth()->id())->latest();
    }
}
