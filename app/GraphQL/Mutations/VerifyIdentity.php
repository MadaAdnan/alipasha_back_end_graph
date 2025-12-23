<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\IdentityEnum;
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

        if (empty($input['passport']) && empty($input['record']) &&
            (empty($input['imageFront']) || empty($input['imageBack']))) {
            throw new GraphQLExceptionHandler('الرجاء إدخال صور');
        }

        try{

            \DB::beginTransaction();
            $identity= Identity::create([
                'user_id' => auth()->id(),
                'status' => IdentityEnum::PENDING->value,
            ]);
            if(!empty($input['imageFront'])){
                $identity->addMedia($input['imageFront'])->toMediaCollection('front');
                $identity->addMedia($input['imageBack'])->toMediaCollection('back');
            }
            if(!empty($input['record'])){
                $identity->addMedia($input['record'])->toMediaCollection('record');
            }
            if(!empty($input['passport'])){
                $identity->addMedia($input['passport'])->toMediaCollection('passport');
            }


            \DB::commit();
            return [
                'user'=>auth()->user(),
                'identity'=>$identity
            ];
        }catch (\Exception | \Error $e){
            \DB::rollBack();
            throw new GraphQLExceptionHandler($e->getMessage());
        }


    }
}
