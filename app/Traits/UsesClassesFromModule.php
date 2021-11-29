<?php


namespace App\Traits;


use Error;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

trait UsesClassesFromModule
{
    use UsesTenantModel;

    public function classFromModule(string $folder, string $className, ?string $default = null)
    {
        return $this->tryNewClassFromModule(
            "Modules\\"
            . $this->getTenantModel()::current()->getModuleName()
            . "\\" . $folder
            . "\\" . $className,
            $default
        );
    }

    public function tryNewClassFromModule(string $className, ?string $default = null)
    {
        try {
            return new $className;
        } catch (Error $error) {
            return $default ? new $default : null;
        }
    }
}
