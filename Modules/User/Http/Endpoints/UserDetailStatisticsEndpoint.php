<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorized;
use App\Takes\TkJson;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\PumpManager\Entities\PMUser;
use Symfony\Component\HttpFoundation\Response;

final class UserDetailStatisticsEndpoint extends Controller
{
    /**
     * @param PMUser $user
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(PMUser $user): Responsable|Response
    {
//        $rates = Rates::new();
//        $projectsByMonths = DB::table(Tenant::current()->database . '.projects')
//            ->where('user_id', $user->id)
//            ->distinct()
//            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as 'month',
//                COUNT(*) as 'count',
//                SUM(count(*)) OVER (ORDER by DATE_FORMAT(created_at, '%Y-%m')) as 'count_with_increasing'")
//            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
//            ->orderByRaw("DATE_FORMAT(created_at, '%Y-%m')")
//            ->get();
        return TkAuthorized::new(
            'user_statistics',
            TkJson::new(fn() => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'discounts' => $user->formatted_discounts,
                'projects' => $user->projects()
                    ->withCount('selections')
                    ->get(['id', 'created_at', 'name']),
//                'projects_by_months' => $projectsByMonths,
            ])
        )->act();
    }
}
