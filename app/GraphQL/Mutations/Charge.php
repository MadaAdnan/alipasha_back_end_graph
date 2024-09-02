<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Coupon;
use App\Models\User;

final class Charge
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        /**
         * @var $coupon Coupon
         */
        $coupon = Coupon::whereNotNull(['user_id', 'bay_at'])
            ->whereNull(['coupons.used_at', 'coupons.used_id'])
            ->where('coupons.is_active',true)
            ->where(['code' => $args['code'], 'password' => $args['password']])
            ->first();

        if (!$coupon) {
            throw new GraphQLExceptionHandler('الكوبون غير موجود');
        }
        /**
         * @var $user User
         */
        $user = auth()->user();

        \DB::beginTransaction();
        try {
            $coupon->update([
                'used_id' => auth()->id(),
                'used_at' => now(),
                'is_active' => false,
            ]);
            $user->balances()->create([
                'credit' => $coupon->price,
                'debit' => 0,
                'info' => 'شحن رصيد من خلال كوبون ' . $args['code'],
            ]);
            \DB::commit();
        } catch (\Exception | \Error $e) {
            \DB::rollBack();
            throw new GraphQLExceptionHandler('حدث خطأ أثناء المعالجة ' . $e->getMessage());
        }
        return $user;
    }
}
