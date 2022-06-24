<?php

namespace App\Interfaces;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * System snapshot
 * @package App\Support
 */
interface Take
{
    /**
     * @param Request|null $request
     * @return Responsable|Response
     */
    public function act(Request $request = null): Responsable|Response;
}
