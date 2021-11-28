<?php


namespace App\Http\Controllers;

class ModuleResourceController extends Controller
{
    protected ?string $indexPath, $createPath, $showPath, $editPath;

    public function __construct($indexPath, $createPath, $showPath, $editPath)
    {
        $this->indexPath = $indexPath;
        $this->createPath = $createPath;
        $this->showPath = $showPath;
        $this->editPath = $editPath;
    }
}
