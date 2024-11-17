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

        \DB::beginTransaction();
        try {
            // إنشاء الفاتورة
            $invoice = new Invoice();
            $invoice->seller_id = $data['seller_id'];
            $invoice->user_id = auth()->id();
            $invoice->phone = auth()->user()->phone;
            $invoice->address = auth()->user()->address;
            $invoice->status = OrderStatusEnum::PENDING->value;
            $invoice->weight = $data['weight'];

            // احفظ الفاتورة أولاً للحصول على $invoice->id
            $invoice->save();

            // حساب الإجمالي وإنشاء العناصر
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
