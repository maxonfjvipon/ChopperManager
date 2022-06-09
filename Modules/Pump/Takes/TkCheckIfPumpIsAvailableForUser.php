<?php

namespace Modules\Pump\Takes;

use App\Takes\Take;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Pump\Entities\Pump;
use Modules\User\Entities\User;
use Symfony\Component\HttpFoundation\Response;

final class TkCheckIfPumpIsAvailableForUser implements Take
{
    /**
     * @param Pump $pump
     * @param Authenticatable|User $user
     * @param Take $origin
     */
    public function __construct(
        private Pump $pump,
        private Authenticatable|User $user,
        private Take $origin)
    {
    }

    /**
     * @inheritDoc
     */
    public function act(Request $request = null): Responsable|Response
    {
        return (new TkWithCallback(
            fn() => abort_if(
                !in_array(
                    $this->pump->id,
                    $this->user->available_pumps()
                        ->onPumpableType($this->pump->pumpable_type)
                        ->pluck($this->pump->getTable() . '.id')
                        ->all()
                ),
                404
            ),
            $this->origin,
        ))->act($request);
    }
}
