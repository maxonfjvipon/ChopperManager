<?php


namespace Modules\AdminPanel\Traits;

use Error;
use Exception;

trait HasClassInModule
{
    public function existedClassInModule($className, $defaultClass)
    {
        try {
            new $className;
            return $className;
        } catch (Error|Exception) {
            return $defaultClass;
        }
    }
}
