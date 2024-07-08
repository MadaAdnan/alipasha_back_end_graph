<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Comment;

final class CreateComment
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        return Comment::create([
            'comment' => $args['comment'],
            'product_id' => $args['product_id'],
            'user_id' => auth()->id(),
        ]);
    }
}
