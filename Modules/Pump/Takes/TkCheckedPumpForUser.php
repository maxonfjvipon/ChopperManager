<?php

namespace Modules\Pump\Takes;

use App\Interfaces\Take;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Modules\Pump\Entities\Pump;
use Modules\User\Entities\User;
use Symfony\Component\HttpFoundation\Response;

final class TkCheckedPumpForUser implements Take
{
    private Take $origin;

    private Pump $pump;

    private Authenticatable|User $user;

    public function __construct(Pump $pump, Authenticatable|User $user, Take $take)
    {
        $this->pump = $pump;
        $this->user = $user;
        $this->origin = $take;
    }

    /**
     * {@inheritDoc}
     */
    public function act(Request $request = null): Responsable|Response
    {
        return (new TkWithCallback(
            fn () => abort_if(
                ! in_array(
                    $this->pump->id,
                    $this->user->available_pumps()
                        ->onPumpableType($this->pump->pumpable_type)
                        ->pluck($this->pump->getTable().'.id')
                        ->all()
                ),
                404
            ),
            $this->origin,
        ))->act($request);
    }
}
