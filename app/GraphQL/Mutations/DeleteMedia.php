<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class DeleteMedia
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $media=Media::find($args['id']);
        if($media){
            $media->delete();
        }
        return $media;
    }
}
