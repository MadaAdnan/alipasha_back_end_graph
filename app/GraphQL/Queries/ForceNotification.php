<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

final class ForceNotification
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
        $notification=auth()->user()->notifications()->whereNull('read_at')->where('data->is_admin',1)->latest()->first();
        return $notification?->data;
    }
}
