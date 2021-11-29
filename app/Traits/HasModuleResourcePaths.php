<?php


namespace App\Traits;


trait HasModuleResourcePaths
{
    abstract public function indexPath();

    abstract public function showPath(): string;

    abstract public function editPath(): string;

    abstract public function createPath(): string;
}
