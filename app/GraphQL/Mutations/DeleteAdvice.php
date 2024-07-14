<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Advice;

final class DeleteAdvice
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $adviceId = $args['id'];
        $userId = auth()->id();

        $advice=Advice::where('user_id',$userId)->find($adviceId);
        if(!$advice){
            throw new \Exception('الإعلان غيرموجود');
        }
            $advice->delete();
        return $advice;
    }
}
