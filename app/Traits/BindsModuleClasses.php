<?php


namespace App\Traits;

use Illuminate\Support\Facades\App;

trait BindsModuleClasses
{
    use UsesClassesFromModule;

    public function bindModuleClasses($array, $folder)
    {
        foreach ($array as $item) {
            $this->app->bind($item['abstract'], function () use ($item, $folder) {
                return $this->app->make($this->classFromModule($folder, $item['name'], $item['default']));
            });
        }

    }
}
