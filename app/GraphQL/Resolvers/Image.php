<?php declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Enums\CategoryTypeEnum;
use App\Models\Product;

final class Image
{

    /**
     * @param $root
     * @return string
     * Get Main Image of Product
     */
    public static function getMain($root): string
    {
        return $root->getFirstMediaUrl('main', 'webp');
    }

    /**
     * @param $root
     * @return string
     * Get Main Image of Product
     */
    public static function getVideo($root): string
    {
        return $root->getFirstMediaUrl('video');
    }
    /**
     * @param $root
     * @return string
     * Get Single IMage from image collection
     */
    public static function getImage($root): string
    {
        return $root->getFirstMediaUrl('image', 'webp');
    }

    /**
     * @param $root
     * @return string
     * Get Array Of Images from image collection
     */
    public static function getImages($root): array
    {
        $mediaItems = $root->getMedia('images');
        return $mediaItems->map(function ($media) {
            return $media->getUrl('webp');
        })->toArray();
    }
    /**
     * @param $root
     * @return array
     * Get Array Of Images with Media ID  from images of other model collection
     */
    public static function getDocsWithId($root): array
    {
        $list = [];
        foreach ($root->getMedia('docs') as $media) {
            $list[] = ["id" => $media->id, 'url' => $media->getUrl()];
        }

        return $list;
    }
    /**
     * @param $root
     * @return array
     * Get Array Of docs  from docs of [job,tender] collection
     */
    public static function getDocs($root): array
    {
        $mediaItems = $root->getMedia('docs');
        return $mediaItems->map(function ($media) {
            return $media->getUrl();
        })->toArray();
    }

    /**
     * @param $root
     * @return array
     * Get Array Of docs with Media ID  from docs of [job,tender] collection
     */
    public static function getImagesWithId($root): array
    {
        $list = [];
        foreach ($root->getMedia('images') as $media) {
            $list[] = ["id" => $media->id, 'url' => $media->getUrl()];
        }

        return $list;
    }
}
