<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;


use App\Models\Comment;
use App\Models\Interaction;

final class CreateComment
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $product=\App\Models\Product::find($args['product_id']);

        if(auth()->check() && $product!=null){
            Interaction::updateOrCreate([
                'user_id'=>auth()->id(),
                'category_id'=>$product->sub1_id,

            ],[
                'visited'=> \DB::raw('visited + 1'),
            ]);
        }

        return Comment::create([
            'comment' => $args['comment'],
            'product_id' => $args['product_id'],
            'user_id' => auth()->id(),
        ]);

    }
}
