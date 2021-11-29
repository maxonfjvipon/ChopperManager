<?php


namespace App\Traits;

trait BindsModuleRequests
{
    use BindsModuleClasses;

    abstract public function requests(): array;

    public function bindModuleRequests()
    {
        $this->bindModuleClasses($this->requests(), 'Http\\Requests');
    }
}
