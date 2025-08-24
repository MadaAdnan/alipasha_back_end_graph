<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Jobs\SendFirebaseNotificationJob;
use App\Models\Interaction;
use App\Models\Like;
use App\Models\Product;
use Exception;

final  class ClickWhatsapp
{
    /** @param array{} $args */
    public function __invoke($_, array $args)
    {
        $productId = $args['productId'];
        $product = Product::find($productId);
        if(!$product){
            throw new GraphQLExceptionHandler('Product not found', 404);
        }
        $user = auth()->user()->name;
        $name = $product->name ?? \Str::substr($product->expert, 0, 20);
        $data['title'] = 'مراسلة جديدة';
        $data['body'] = "قد يتواصل الزبون {$user} عبر واتسأب للإستفسار عن المنتج {$name}";
        try {
            $job = new SendFirebaseNotificationJob([$product->user->device_token], $data);
            dispatch($job);
        } catch (Exception|\Error $e) {

        }

        return $product->refresh();
    }
}
