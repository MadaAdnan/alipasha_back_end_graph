<?php

namespace App\Traits;

use App\Enums\CategoryTypeEnum;
use App\Models\Product;
use App\Models\User;
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
                ->quality(95)
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
        $typeAllow=[
             CategoryTypeEnum::PRODUCT->value,
            CategoryTypeEnum::RESTAURANT->value,
            CategoryTypeEnum::NEWS->value
        ];
        if ($this instanceof Product && ! in_array($this->type, $typeAllow)) {
            return $this->user?->getImage() ?? asset('images/noImage.jpeg');
        }
        if ($this->hasMedia($collection)) {
            return $this->getFirstMediaUrl($collection, $conversation);
        }else if ($this->hasMedia('images')) {
        return $this->getFirstMediaUrl('images', $conversation);
    } elseif ($collection == 'logo') {
            return asset('images/bg.jpg');
        } elseif ($this instanceof User && $collection == 'image') {
            return asset('images/user-profile.png');
        } else {
            return asset('images/noImage.jpeg');
        }

    }

    public function getImageSiteMap(): null|string
    {

        if ($this->hasMedia('image')) {
            return $this->getFirstMediaUrl('image', 'webp');
        } else if ($this->hasMedia('images')) {
            return $this->getFirstMediaUrl('images', 'webp');
        }
        return null;

    }

    /**
     * get all Images for any Model
     * */
    public function getImages($collection = 'image', $conversation = 'webp'): array
    {
        $list = [];
        if ($this instanceof Product && $collection == 'image' && !$this->hasMedia('image')) {
            $collection = 'images';
        }
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

    public function getImageForceSiteMap()
    {
        if ($this->hasMedia('image')) {
            return $this->getFirstMediaUrl('image', 'webp');
        } elseif ($this->hasMedia('images')) {
            return $this->getFirstMediaUrl('images', 'webp');
        }
        return null;

    }
}
