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
            $ship_price = ShippingPrice::where('weight', '>=', $data['weight'])->orderBy('weight')->first();
            if ($ship_price == null) {
                $ship_price = ShippingPrice::orderBy('weight', 'desc')->first();

            }
            if ($ship_price == null) {
                throw new \Exception('لا يتوفر توصيل حالياً');
            }
            $far = 0;
            $seller = User::findOrFail($data['seller_id']);
            if (auth()->user()->getIsSameMainCity($seller)) {
                $far = $ship_price->internal_price;
            } else {
                $far = $ship_price->external_price;
            }
            $invoice = new Invoice();
            $invoice->seller_id = $data['seller_id'];
            $invoice->user_id = auth()->id();
            $invoice->phone = auth()->user()->phone;
            $invoice->address = auth()->user()->address;
            $invoice->status = OrderStatusEnum::PENDING->value;
            $invoice->weight = $data['weight'];
            $invoice->shipping = $far;
            $invoice->save();
            $total = 0;
            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);
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
            $invoice->total = $total;
            $invoice->save();

            \DB::commit();
            return $invoice;
        } catch (\Exception | \Error $e) {
            \DB::rollBack();
            throw new GraphQLExceptionHandler($e->getMessage());
        }
    }
}
