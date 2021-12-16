<?php


namespace App\Traits;


use Error;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

trait UsesClassesFromModule
{
    use UsesTenantModel;

    public function classFromModule(string $folder, string $className, ?string $default = null): ?string
    {
        return $this->tryNewClassFromModule(
            "Modules\\"
            . $this->getTenantModel()::current()->getModuleName()
            . "\\" . $folder
            . "\\" . $className,
            $default
        );
    }

    public function tryNewClassFromModule(string $className, ?string $default = null): ?string
    {
        return class_exists($className) ? $className : $default;
    }
}
