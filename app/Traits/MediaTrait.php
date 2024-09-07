<?php

namespace App\Traits;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait MediaTrait
{
    use InteractsWithMedia;

    /**
     * Register Conversation to webp Format
     * */
    public function registerMediaConversions(Media $media = null): void
    {
info('MEdia Type '.$media->mime_type);
        $this
            ->addMediaConversion('webp')
            ->quality(60)
//            ->fit(Manipulations::FIT_STRETCH, 600,0)
            ->format('webp')
            ->nonQueued();

    }

    /**
     * get main Image for any Model
     * */
    public function getImage($collection = 'image', $conversation = 'webp'): string
    {
        return $this->getFirstMediaUrl($collection, $conversation);
    }

    /**
     * get all Images for any Model
     * */
    public function getImages($collection = 'image', $conversation = 'webp'): array
    {
        $list = [];
        foreach ($this->getMedia($collection) as $media) {
            $list[] = $media->getUrl($conversation);
        }
        return $list;
    }

    /**
     * get all Images with MediaId For Delete Media for any Model
     * */
    public function getMediaWithId($collection = 'image'): array
    {
        $files = [];
        if ($this->hasMedia($collection)) {
            foreach ($this->getMedia($collection) as $media) {
                $files[$media->id] = $media->getUrl();
            }
        }
        return collect($files);
    }
}
