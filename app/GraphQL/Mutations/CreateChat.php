<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\CommunityTypeEnum;
use App\Events\CreateCommunityEvent;
use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Community;
use App\Models\User;
use Mockery\Exception;

final class CreateChat
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $userId = auth()->id();
        $memberId = $args['memberId'];
        try {
            $member = User::find($memberId);

            $community = Community::whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })->whereHas('users', function ($query) use ($memberId) {
                $query->where('users.id', $memberId);
            })->where('type', CommunityTypeEnum::CHAT->value)
                ->withCount('users')
                ->having('users_count', '=', 2)
                ->first();

            if ($community === null) {
                $community = Community::create([
                    'last_update' => now(),
                    'manager_id' => $userId,
                    'name' => auth()->user()->name . ' - ' . $member?->seller_name,
                    'type' => CommunityTypeEnum::CHAT->value,
                ]);

                $community->users()->sync([$userId, $memberId]);
                try {
                    broadcast(new CreateCommunityEvent($community));
                } catch (Exception | \Error $e) {

                }
            }
            return [
                'community' => $community,
                'user' => auth()->user()
            ];
        } catch (\Exception | \Error $e) {
            throw new GraphQLExceptionHandler($e->getMessage());
        }

    }
}
