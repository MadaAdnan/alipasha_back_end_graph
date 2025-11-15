<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Jobs\SendFirebaseNotificationJob;
use App\Models\ClickWhats;
use App\Models\Interaction;
use App\Models\Like;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Log;

final  class ClickWhatsapp
{
    /** @param array{} $args */
    public function __invoke($_, array $args)
    {
        $productId = $args['productId'];
        $product = Product::find($productId);

        if (!$product) {
            throw new GraphQLExceptionHandler('Product not found', 404);
        }
        $user = auth()->user()->name;
        $name = $product->name ?? \Str::substr($product->expert, 0, 20);
        $data['title'] = 'مراسلة جديدة';
        $data['body'] = "قد يتواصل الزبون {$user} عبر واتسأب للإستفسار عن المنتج {$name}";
        try {

            $job = new SendFirebaseNotificationJob([$product->user->device_token], $data);
            dispatch($job);
            ClickWhats::create([
                'product_id' => $productId,
                'user_id' => $user->id,
                'seller_id' => $product->user_id
            ]);
        } catch (Exception|\Error $e) {
Log::error($e->getMessage());
        }

        return $product->refresh();
    }
}
