<?php


namespace Modules\Selection\Transformers\SelectionResources;


use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Support\TenantStorage;
use Modules\Selection\Traits\ConstructsSelectionCurves;

abstract class PumpSelectionResource extends JsonResource
{
    use ConstructsSelectionCurves;

    /*
     * Transform string to array of integers
     */
    protected function arrayOfIntsFromString($string): array
    {
        return $string === null ? [] : array_map('intval', explode(",", $string));
    }

    protected function pumpInfo(): array
    {
        $pump = $this->pump;
        $tenantStorage = new TenantStorage();
        return [
            'article_num_main' => $pump->article_num_main, //
            'article_num_reserve' => $pump->article_num_reserve, //
            'article_num_archive' => $pump->article_num_archive, //
            'full_name' => $pump->full_name, //
            'weight' => $pump->weight, //
            'rated_power' => $pump->rated_power, //
            'rated_current' => $pump->rated_current, //
            'connection_type' => $pump->connection_type->name, //
            'fluid_temp_min' => $pump->fluid_temp_min, //
            'fluid_temp_max' => $pump->fluid_temp_max, //
            'ptp_length' => $pump->ptp_length, //
            'dn_suction' => $pump->dn_suction->value, //
            'dn_pressure' => $pump->dn_pressure->value, //
            'category' => $pump->series->category->name, //
            'power_adjustment' => $pump->series->power_adjustment->name, //
            'connection' => $pump->mains_connection->full_value, //
            'applications' => $pump->applications, //
            'types' => $pump->types, //
            'description' => $pump->description,
            'images' => [
                'pump' => $tenantStorage->urlToImage($pump->image),
                'sizes' => $tenantStorage->urlToImage($pump->sizes_image),
                'electric_diagram' => $tenantStorage->urlToImage($pump->electric_diagram_image),
                'cross_sectional_drawing' => $tenantStorage->urlToImage($pump->cross_sectional_drawing_image),
            ],
            'files' => $pump->files
                ->map(fn($file) => $tenantStorage->urlToFile($file->file_name))
                ->filter(fn($file) => $file != null)
                ->map(fn($file) => [
                    'name' => basename($file),
                    'link' => $file
                ])
        ];
    }
}
