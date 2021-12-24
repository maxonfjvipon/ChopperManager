<?php

namespace Modules\Pump\Transformers\Pumps;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Support\TenantStorage;

class PumpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $storage = new TenantStorage();
        return [
            'images' => [
                'pump' => $storage->urlToImage($this->image),
                'sizes' => $storage->urlToImage($this->sizes_image),
                'electric_diagram' => $storage->urlToImage($this->electric_diagram_image),
                'cross_sectional_drawing' => $storage->urlToImage($this->cross_sectional_drawing_image),
            ],
            'files' => $this->files
                ->map(fn($file) => $storage->urlToFile($file->file_name))
                ->filter(fn($file) => $file != null)
                ->map(fn($file) => [
                    'name' => basename($file),
                    'link' => $file
                ])
        ];
    }
}
