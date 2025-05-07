<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use Illuminate\Notifications\DatabaseNotification;

final class NotificationsQuery
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        /**
         * @var $notifications
         */

        $notifications= auth()->user()?->notifications()
            ->where(function ($query) {
                $query->where('data->is_admin', '!=', 1)
                    ->orWhereNull('data->is_admin');
            })
            ->latest();
        auth()->user()->unreadNotifications->markAsRead();
        return $notifications;
    }
}
