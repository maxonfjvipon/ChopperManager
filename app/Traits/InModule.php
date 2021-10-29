<?php


namespace App\Traits;


use Illuminate\Support\Facades\Config;
use JetBrains\PhpStorm\Pure;
use Nwidart\Modules\Facades\Module;

trait InModule
{
    #[Pure] public function toAdminModule($path): string
    {
        return join("/", ['AdminPanel', $path]);
    }
}
