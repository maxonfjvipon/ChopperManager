<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\User\Entities\User;
use Modules\User\Support\UserToEdit;
use Symfony\Component\HttpFoundation\Response;

/**
 * Users edit endpoint
 */
final class EpEditUser extends Controller
{
    /**
     * @param int $user_id
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(int $user_id): Responsable|Response
    {
        return (new TkInertia(
            'User::Edit',
            new UserToEdit(User::find($user_id))
        ))->act();
    }
}
