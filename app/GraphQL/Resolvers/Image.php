<?php declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Enums\CategoryTypeEnum;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

final class Image
{

    /**
     * @param $root
     * @return string
     * Get Main Image of Product
     */
    public static function getMain($root): string
    {
        return $root->getFirstMediaUrl('image', 'webp') ?? asset('images/noImage.jpeg');
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
        if ($root->hasMedia('image')) {
            return $root->getFirstMediaUrl('image', 'webp');
        }elseif ($root->hasMedia('images')){
            return $root->getFirstMediaUrl('images', 'webp');
        }elseif($root instanceof  User){
            return url('/') . asset('images/user-profile.png');
        }
        elseif($root->user!=null && $root->user->hasMedia('image')){
            return $root->user->getFirstMediaUrl('image', 'webp');
        }
        return url('/') . asset('images/noImage.jpeg');
    }

    /**
     * @param $root
     * @return string
     * Get Single IMage from image collection
     */
    public static function getCustomImage($root): string
    {
        if ($root->hasMedia('custom')) {

            return $root->getFirstMediaUrl('custom', 'webp');
        }
        return url('/') . asset('images/noImage.jpeg');
    }

    /**
     * @param $root
     * @return string
     * Get Single IMage from logo collection
     */
    public static function getLogo($root): string
    {
        if ($root->hasMedia('logo')) {
            return $root->getFirstMediaUrl('logo', 'webp');
        }
        return url('/') . asset('images/bg.jpg');
    }


    /**
     * @param $root
     * @return string
     * Get Array Of Images from image collection
     */
    public static function getImages($root): array
    {
        if ($root->hasMedia('images')) {
            $mediaItems = $root->getMedia('images');
            return $mediaItems->map(function ($media) {
                return $media->getUrl('webp');
            })->toArray();
        }
        return [];


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
    public static function getImageGallery($root): array
    {
        $list = [];
        foreach ($root->getMedia('gallery') as $media) {
            $list[] = ["id" => $media->id, 'url' => $media->getUrl()];
        }

        return $list;
    }

    /**
     * @param $root
     * @return array[string]
     * Get Array Of docs  from docs of [job,tender] collection
     */
    public static function getDocs($root): array
    {
        if ($root->hasMedia('docs')) {
            $mediaItems = $root->getMedia('docs');
            return $mediaItems->map(function ($media) {
                return $media->getUrl();
            })->toArray();
        }
        return [];
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

    public static function getAttach($root): string|null
    {
        if ($root->hasMedia('attach')) {

            return $root->getFirstMediaUrl('attach');
        }
        return null;
    }
}
