<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

final class NotificationsQuery
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        return auth()->user()?->notifications()->latest();
    }
}
