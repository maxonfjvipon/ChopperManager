<?php


namespace App\Traits;

trait BindsModuleServices
{
    use BindsModuleClasses;

    abstract public function services(): array;

    public function bindModuleServices()
    {
        $this->bindModuleClasses($this->services(), 'Services');
    }
}
