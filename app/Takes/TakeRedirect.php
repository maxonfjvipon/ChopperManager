<?php

namespace App\Takes;

use Illuminate\Http\RedirectResponse;

/**
 * For redirect endpoints
 * @package App\Takes
 */
interface TakeRedirect
{
    /**
     * @return RedirectResponse
     */
    public function redirect(): RedirectResponse;
}
