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
       $identity= Identity::create([
            'user_id' => auth()->id(),
            'status' => 'pending'
        ]);
        return $identity;
    }
}
