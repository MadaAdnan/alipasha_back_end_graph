<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Comment;

final class DeleteComment
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $id = $args['id'];
        $comment = Comment::find($id);
        if ($comment->user_id != auth()->id() && $comment->product->user_id != auth()->id()) {
            throw new GraphQLExceptionHandler('ليس لديك إذن بحذف التعليق');
        }
        return $comment->delete();
    }
}
