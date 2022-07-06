<?php

namespace App\Http\Middleware;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;

/**
 * Handle Inertia requests middleware.
 */
final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param Request $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function doesRequestContain($request, $what): bool
    {
        return str_contains($request->getRequestUri(), $what);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function share(Request $request): array
    {
        return array_merge(
            parent::share($request),
            [
                'title' => 'BPE Pump Master',
                'auth' => array_merge([
                    'full_name' => Auth::user()->full_name ?? null,
                ], Auth::user()?->isAdmin()
                    ? ['is_admin' => true]
                    : []),
                'flash' => function () use ($request) {
                    return [
                        'success' => $request->session()->get('success'),
                        'warning' => $request->session()->get('warning'),
                        'info' => $request->session()->get('info'),
                        'error' => $request->session()->get('error'),
                        'errorBag' => $request->session()->get('errorBag'),
                    ];
                }],
            $this->doesRequestContain($request, "selections")
                ? [
                'selection_types' => [
                    SelectionType::fromValue(SelectionType::Auto)->key => SelectionType::fromValue(SelectionType::Auto)->key,
                    SelectionType::fromValue(SelectionType::Handle)->key => SelectionType::fromValue(SelectionType::Handle)->key,
                ],
                'station_types' => [
                    StationType::fromValue(StationType::WS)->key => StationType::fromValue(StationType::WS)->key,
                    StationType::fromValue(StationType::AF)->key => StationType::fromValue(StationType::AF)->key
                ],
            ]
                : []
        );
    }
}
