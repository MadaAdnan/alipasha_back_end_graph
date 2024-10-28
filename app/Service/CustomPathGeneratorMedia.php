<?php

namespace App\Service;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGeneratorMedia implements PathGenerator
{

    public function getPath(Media $media): string
    {
       return md5(date('Y-m').$media->id).'/image/';
    }

    public function getPathForConversions(Media $media): string
    {
        return md5(date('Y-m')).'/conversation/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return md5(date('Y-m').$media->id).'/responsive';
    }
}
