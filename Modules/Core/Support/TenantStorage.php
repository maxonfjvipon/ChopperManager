<?php

namespace Modules\Core\Support;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Modules\AdminPanel\Entities\Tenant;

class TenantStorage
{
    private Filesystem $storage;
    private string $noImageName = "no_image.jpg";
    private string $currentTenantFolder;

    public function __construct($disk = 'media')
    {
        $this->storage = Storage::disk($disk);
        $this->currentTenantFolder = Tenant::current()->id . '/';
    }

    public function urlToTenantFolder(): string
    {
        return $this->storage->url($this->currentTenantFolder);
    }

    public function urlToImage($name): string
    {
        $fullPath = $this->currentTenantFolder . $name;
        return ($name && $this->storage->exists($fullPath))
            ? $this->storage->url($fullPath)
            : $this->storage->url($this->noImageName);
    }

    public function urlToFile($name): ?string
    {
        $fullPath = $this->currentTenantFolder . $name;
        return ($name && $this->storage->exists($fullPath))
            ? $this->storage->url($name)
            : null;
    }

    public function putFileTo($folder, $file): bool|string
    {
        return $this->storage->putFileAs($this->currentTenantFolder . $folder, $file, $file->getClientOriginalName());
    }
}
