<?php

namespace Modules\Pump\Http\Endpoints\Pump;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Http\Requests\AddPumpToProjectsRequest;

/**
 * Pumps add to projects endpoint
 */
final class PumpsAddToProjectsEndpoint extends Controller
{
    /**
     * @param AddPumpToProjectsRequest $request
     * @param Pump $pump
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function __invoke(AddPumpToProjectsRequest $request, Pump $pump): JsonResponse
    {
        $this->authorize('pump_show');
        foreach ($request->project_ids as $project_id) {
            $this->authorize('project_access_' . $project_id);
        }
        $selections = [];
        foreach ($request->project_ids as $project_id) {
            $selections[] = [
                'project_id' => $project_id,
                'selected_pump_name' => $request->pumps_count . ' ' . $pump->full_name,
                'pumps_count' => $request->pumps_count,
                'main_pumps_counts' => $request->pumps_count,
                'pump_id' => $pump->id,
            ];
        }
        DB::table('selections')->insert($selections);
        return response()->json(['success', 'Pump was added successfully']);
    }
}
