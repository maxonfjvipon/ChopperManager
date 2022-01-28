<?php

namespace App\Support;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Can render through __invoke method
 * @package App\Support
 */
interface Renderable
{
    /**
     * @param Request|null $request
     * @return Responsable|Response
     */
    public function render(Request $request = null): Responsable|Response;
}
