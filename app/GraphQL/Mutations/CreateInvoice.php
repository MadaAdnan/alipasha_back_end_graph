<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\OrderStatusEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Product;
use App\Models\ShippingPrice;
use App\Models\User;

final  class CreateInvoice
{
    /** @param array{} $args */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];

        \DB::beginTransaction();
        try {

            $weight=0;
            if(!auth()->check() || auth()->id()==null){
                throw new GraphQLExceptionHandler('خطأ في الطلب يرجى المحاولة من جديد');
            }
            $invoice = new Invoice();
            $invoice->seller_id = $data['seller_id'];
            $invoice->user_id = auth()->id();
            $invoice->phone =$data['phone'] ??auth()->user()->phone;
            $invoice->address =$data['address'] ?? auth()->user()->address;
            $invoice->status = OrderStatusEnum::PENDING->value;
            $invoice->save();

            $seller = User::findOrFail($data['seller_id']);

            $total = 0;
            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);
                if($product->is_delivery){
                    $weight+=$product->weight;
                }
                if (!$product) {
                    throw new \Exception("Product with ID {$item['product_id']} not found.");
                }
                $price = $product->is_discount ? $product->discount : $product->price;
                $total_price = $price * $item['qty'];
                Item::create([
                    'invoice_id' => $invoice->id, // $invoice->id متاح الآن
                    'product_id' => $item['product_id'],
                    'price' => $price,
                    'qty' => $item['qty'],
                    'total' => $total_price,
                ]);
                $total += $total_price;
            }
            $ship_price = ShippingPrice::where('weight', '>=', $weight)->orderBy('weight')->first();
            if ($ship_price == null) {
                throw new \Exception('لا يتوفر توصيل حالياً');
            }
            // set Far
            if($weight==0){
                $far=0;
            }elseif (auth()->user()->getIsSameMainCity($seller)) {
                $far = $ship_price->internal_price;
            } else {
                $far = $ship_price->external_price;
            }
            $invoice->weight = $weight;
            $invoice->shipping = $far;
            $invoice->total = $total;
            $invoice->save();
            \DB::commit();
            return $invoice;
        } catch (\Exception | \Error $e) {
            \DB::rollBack();
            throw new GraphQLExceptionHandler($e->getMessage().'ase');
        }
    }
}
