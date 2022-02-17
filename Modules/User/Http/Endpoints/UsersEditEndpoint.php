<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkInertia;
use Illuminate\Contracts\Support\Responsable;
use Modules\PumpManager\Entities\PMUser;
use Modules\User\Support\UserToEdit;
use Symfony\Component\HttpFoundation\Response;

/**
 * Users edit endpoint
 */
final class UsersEditEndpoint extends Controller
{
    /**
     * @param int $user_id
     * @return Responsable|Response
     */
    public function __invoke(int $user_id): Responsable|Response
    {
        return TkAuthorized::new(
            'user_edit',
            TkInertia::new(
                'User::Edit',
                UserToEdit::new(PMUser::find($user_id))
            )
        )->act();
    }
}
