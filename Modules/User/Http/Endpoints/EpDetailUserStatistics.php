<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkAuthorize;
use App\Takes\TkJson;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\User\Entities\User;
use Symfony\Component\HttpFoundation\Response;

/**
 * Detail user statistics endpoint.
 */
final class EpDetailUserStatistics extends Controller
{
    /**
     * @throws Exception
     */
    public function __invoke(User $user): Responsable|Response
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
        return (new TkAuthorize(
            'user_statistics',
            new TkJson(fn () => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'discounts' => $user->formatted_discounts,
                'projects' => $user->projects()
                    ->withCount('selections')
                    ->get(['id', 'created_at', 'name']),
            ])
        ))->act();
    }
}
