<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Identity;

final  class VerifyIdentity
{
    /** @param array{} $args */
    public function __invoke($_, array $args)
    {
        $input = $args['input'];
        if (Identity::where([
            'user_id' => auth()->id(),
            'status' => 'pending'
        ])->exists()) {
            throw new GraphQLExceptionHandler('لديك طلب توثيق سابق وهو قيد المراجعة');
        }
        try{
            throw new GraphQLExceptionHandler(json_encode($input));
            \DB::beginTransaction();
            $identity= Identity::create([
                'user_id' => auth()->id(),
                'status' => 'pending'
            ]);
            $identity->addMedia($input['front'])->toMediaCollection('front');
            $identity->addMedia($input['back'])->toMediaCollection('back');
            \DB::commit();
            return $identity;
        }catch (\Exception | \Error $e){
            \DB::rollBack();
            throw new GraphQLExceptionHandler($e->getMessage());
        }


    }
}
