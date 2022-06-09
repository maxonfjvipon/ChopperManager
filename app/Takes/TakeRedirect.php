<?php

namespace App\Takes;

use App\Takes\Take;
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
