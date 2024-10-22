<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Models\User;

final class AddToGallery
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        /**
         * @var $user User
         */
        $user = auth()->user();
        $image = $args['image'];
        if (!isset($data['image']) || $data['image'] === null) {
            throw new GraphQLExceptionHandler('يرجى تحديد صورة صحيحة' . implode('-',$args));
        }
        $type = $image->getClientOriginalExtension();
        if(!in_array($type,['png','webp','jpeg','jpg'])){
            throw new GraphQLExceptionHandler('صيغة الملف غير صالحة '.$type);
        }
        try {
            $media = $user->addMedia($image)->toMediaCollection('gallery');
            return [
                'id' => $media->id,
                'url' => $media->getUrl('webp'),
            ];
        } catch (\Exception | \Error $e) {
            throw new GraphQLExceptionHandler($e->getMessage());
        }

    }
}
