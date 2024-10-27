<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;


use App\Models\Comment;
use App\Models\Interaction;
use App\Service\SendNotifyHelper;

final class CreateComment
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $product = \App\Models\Product::find($args['product_id']);

        if (auth()->check() && $product != null) {
            Interaction::updateOrCreate([
                'user_id' => auth()->id(),
                'category_id' => $product->sub1_id,

            ], [
                'visited' => \DB::raw('visited + 1'),
            ]);
        }
        try {
            $user = $product->user;
            $data['title'] = 'تعليق جديد بواسطة ' . $user->name;
            $data['body'] = 'تم التعليق على منتجك  ' . $product->name ?? $product->expert;
            $data['url'] = 'https://ali-pasha.com/comments?id=' . $product->id;

            SendNotifyHelper::sendNotify($user, $data);
        } catch (\Exception | \Error $e) {
        }
        return Comment::create([
            'comment' => $args['comment'],
            'product_id' => $args['product_id'],
            'user_id' => auth()->id(),
        ]);

    }
}
