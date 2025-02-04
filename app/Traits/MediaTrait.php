<?php

namespace App\Traits;

use App\Enums\CategoryTypeEnum;
use App\Models\Product;
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
        $mims = [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/webp',
        ];
        if (in_array($media?->mime_type, $mims)) {
            $this
                ->addMediaConversion('webp')
                ->quality(80)
//            ->fit(Manipulations::FIT_STRETCH, 600,0)
                ->format('webp')
                ->nonQueued();

        }

    }

    /**
     * get main Image for any Model
     * */
    public function getImage($collection = 'image', $conversation = 'webp'): string
    {
        if($this instanceof  Product && ($this->type!=CategoryTypeEnum::PRODUCT->value && $this->type!=CategoryTypeEnum::RESTAURANT->value && $this->type!=CategoryTypeEnum::NEWS->value)){
            return $this->user?->getImage()??asset('images/noImage.jpeg');
        }
        if($this->hasMedia($collection)){
            return $this->getFirstMediaUrl($collection, $conversation);
        }else{
            return asset('images/noImage.jpeg');
        }

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

    public function getImageForce()
    {
        if ($this->hasMedia('image')) {
            return $this->getFirstMediaUrl('image', 'webp');
        } elseif ($this->hasMedia('images')) {
            return $this->getFirstMediaUrl('images', 'webp');
        } else {
            return asset('images/noImage.jpeg');
        }

    }
}
