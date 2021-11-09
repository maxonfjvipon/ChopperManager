<?php

namespace Modules\Core\Support;

use Illuminate\Support\Facades\Storage;

class StorageMediaHelper
{
    private static string $DISK = "media";

    public static function getImage($name): string
    {
        $storage = Storage::disk(self::$DISK);
        return $storage->exists($name)
            ? $storage->url($name)
            : $storage->url('no_image.jpg');
    }

    public static function getFile($name): ?string
    {
        $storage = Storage::disk(self::$DISK);
        return $storage->exists($name)
            ? $storage->url($name)
            : null;
    }
}
