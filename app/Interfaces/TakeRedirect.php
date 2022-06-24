<?php

namespace App\Interfaces;

use Illuminate\Http\RedirectResponse;

/**
 * For redirect endpoints
 * @package App\Takes
 */
interface TakeRedirect extends Take
{
    /**
     * @return RedirectResponse
     */
    public function redirect(): RedirectResponse;
}
