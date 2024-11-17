<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\OrderStatusEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Product;

final  class CreateInvoice
{
    /** @param array{} $args */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        throw new GraphQLExceptionHandler(implode('-',array_keys($data)));
        \DB::beginTransaction();
        try {
            $invoice = new Invoice();
            $invoice->seller_id = $data['seller_id'];
            $invoice->user_id = auth()->id();
            $invoice->phone = auth()->user()->phone;
            $invoice->address = auth()->user()->address;
            $invoice->status = OrderStatusEnum::PENDING->value;
            $total = 0;
            $invoice->weight = $data['weight'];
            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);
                $price = $product->is_discount ? $product->discount : $product->price;
                $total_price = $price * $item['qty'];
                Item::create([
                    'invoice_id' => $invoice->id,
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
