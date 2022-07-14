<?php

namespace App\Interfaces;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * System snapshot.
 */
interface Take
{
    public function act(Request $request = null): Responsable|Response;
}
