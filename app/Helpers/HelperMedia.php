<?php

namespace App\Helpers;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class HelperMedia
{
    public static function getFileUpload($label = 'صورة', $name = 'image', $collection = 'image', $is_multible = false, $ratio = ['1:1', '2:1', '1:2'])
    {
        return SpatieMediaLibraryFileUpload::make($name)->collection($collection)->conversion('webp')->label($label)->multiple($is_multible)->image()->imageEditor()->imageEditorAspectRatios($ratio)->openable()->downloadable();

    }

    public static function getImageColumn($label = 'صورة', $name = 'image', $collection = 'image')
    {
        return SpatieMediaLibraryImageColumn::make($name)->collection($collection)->conversion('webp')->label($label)->circular();
    }
}
