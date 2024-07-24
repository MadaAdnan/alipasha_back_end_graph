<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\OrderStatusEnum;
use App\Models\Balance;
use App\Models\City;
use App\Models\Order;
use App\Models\ShippingPrice;

final class CreateOrder
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        $size = ($data['length'] * $data['height'] * $data['width']) / 1000;
        $maxWeight = ShippingPrice::where('weight', '>=', $data['weight'])
            ->orderBy('size')
            ->union(
                ShippingPrice::orderBy('weight', 'desc')->limit(1)
            )
            ->first();
        $maxSize = ShippingPrice::where('size', '>=', $size)
            ->orderBy('size')
            ->union(
                ShippingPrice::orderBy('size', 'desc')->limit(1)
            )
            ->first();
        $from = City::find($data['from_id'])->first();
        $to = City::find($data['to_id'])->first();


        if ($from?->city_id != $to?->city_id) {
            $price = $maxSize?->external_price > $maxWeight?->external_price ? $maxSize?->external_price : $maxWeight?->external_price;
        } else {
            $price = $maxSize?->internal_price > $maxWeight?->internal_price ? $maxSize?->internal_price : $maxWeight?->internal_price;

        }
        $total_balance = \DB::table('balances')->where('user_id', auth()->id())->selectRaw('SUM(credit) - SUM(debit) as total')->first()?->total ?? 0;
        if ($total_balance <= 0 || $total_balance < $price) {
            throw new \Exception('لا تملك رصيد كاف لطلب الشحن');
        }
        \DB::beginTransaction();
        try {


            $order = Order::create([
                'user_id' => auth()->id(),
                'from_id'=>$data['from_id'],
                'to_id'=>$data['to_id'],
                'status' => OrderStatusEnum::PENDING->value,
                'sender_name' => $data['sender_name'],
                'sender_phone' => $data['sender_phone'],
                'receive_address' => $data['receive_address'],
                'receive_phone' => $data['receive_phone'],
                'receive_name' => $data['receive_name'],
                'weight' => $data['weight'],
                'length' => $data['length'],
                'height' => $data['height'],
                'width' => $data['width'],
                'price' => $price,
            ]);

            Balance::create([
                'user_id' => auth()->id(),
                'debit' => $price,
                'credit' => 0,
                'info' => 'خصم أجور شحن طلب رقم #' . $order->id
            ]);
            \DB::commit();
            return [
                'user' => auth()->user(),
                'order' => $order
            ];

        } catch (\Exception | \Error $exception) {
            \DB::rollBack();
            throw new \Exception('حصل خطأ في الطلب يرجى المحاولة لاحقا' .$exception->getMessage());
        }


    }
}
