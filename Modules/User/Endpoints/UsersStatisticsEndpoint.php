<?php

namespace Modules\User\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkJson;
use Illuminate\Contracts\Support\Responsable;
use Modules\PumpManager\Entities\PMUser;
use Symfony\Component\HttpFoundation\Response;

class UsersStatisticsEndpoint extends Controller
{
    /**
     * @param PMUser $user
     * @return Responsable|Response
     */
    public function __invoke(PMUser $user): Responsable|Response
    {
        return TkAuthorized::new(
            'user_statistics',
            TkJson::new(fn() => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'discounts' => $user->formatted_discounts,
                'projects' => $user->projects()
                    ->withCount('selections')
                    ->get(['id', 'created_at', 'name']),
            ])
        )->act();
    }
}
