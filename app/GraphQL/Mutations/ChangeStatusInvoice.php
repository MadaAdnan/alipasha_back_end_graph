<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\OrderStatusEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Invoice;

final  class ChangeStatusInvoice
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
        $invoice=Invoice::find($args['invoiceId']);
        if(!$invoice){
            throw new GraphQLExceptionHandler('الطلب غير موجود');
        }
        if($invoice->seller_id !=auth()->id() && $invoice->user_id!=auth()->id()){
            throw new GraphQLExceptionHandler('ليس لديك الإذن بالعملية');
        }
        $status_arry=[

            OrderStatusEnum::CANCELED->value,
            OrderStatusEnum::CONFIRM_COMPLETE->value,

        ];
        if($invoice->seller_id ==auth()->id()){
            $status_arry[]= OrderStatusEnum::AGREE->value;
        }
        if(!in_array($args['status'],$status_arry)){
            throw new GraphQLExceptionHandler('الحالة غير صحيحة');
        }
        $invoice->update([
            'status'=>$args['status'],
            'seller_note'=>$args['msg']??'',
        ]);
        return $invoice->refresh();
    }
}
