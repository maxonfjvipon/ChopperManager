<?php


namespace App\Traits;

trait BindsModuleClasses
{
    use UsesClassesFromModule;

    public function bindModuleClasses($array, $folder)
    {
        foreach ($array as $item) {
            $this->app->bind($item['abstract'], function () use ($item, $folder) {
                return $this->classFromModule($folder, $item['name'], $item['default']);
            });
        }

    }
}
