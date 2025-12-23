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
        if (!Identity::where([
            'user_id' => auth()->id(),
            'status' => 'pending'
        ])->exists()) {
            throw new GraphQLExceptionHandler('لديك طلب توثيق سابق وهو قيد المراجعة');
        }
        try{
            $identity= Identity::create([
                'user_id' => auth()->id(),
                'status' => 'pending'
            ]);
            $identity->addMedia($input['front'])->toMediaCollection('front');
            $identity->addMedia($input['back'])->toMediaCollection('back');
            return $identity;
        }catch (\Exception | \Error $e){
            throw new GraphQLExceptionHandler($e->getMessage());
        }


    }
}
