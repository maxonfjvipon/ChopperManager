<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Maxonfjvipon\Elegant_Elephant\Text\TxtLowered;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Core\Entities\Currency;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use Spatie\Multitenancy\Models\Tenant;

class HandleInertiaRequests extends Middleware
{
    use UsesTenantModel;

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
     */
    public function share(Request $request): array
    {
        $flash = [
            'flash' => function () use ($request) {
                return [
                    'reload' => $request->session()->get('reload'),
                    'success' => $request->session()->get('success'),
                    'warning' => $request->session()->get('warning'),
                    'info' => $request->session()->get('info'),
                    'error' => $request->session()->get('error'),
                    'errorBag' => $request->session()->get('errorBag'),
                ];
            },
        ];
        if (Tenant::checkCurrent()) {
            $supported_locales = config('app.supported_locales');
            $current_localized = [];
            foreach ($supported_locales as $locale) {
                $current_localized[$locale] = LaravelLocalization::getLocalizedURL($locale, null, [], true);
            }
            $currentTenant = $this->getTenantModel()::current();
            $user = Auth()->user();
            return array_merge(parent::share($request), [
                'title' => $currentTenant->name,
                'auth' => function () use ($user) {
                    return [
                        'full_name' => Auth::check() ? $user->full_name : null,
                        'currency' => Auth::check()
                            ? TxtLowered::new(
                                Currency::allOrCached()->firstWhere('id', Auth::id())->symbol
                            )->asString()
                            : null,
                        'permissions' => Auth::check()
                        ? $user->getPermissionsViaRoles()->map(fn($permission) => $permission->name)
                        : null,
                    ];
                },
                'locales' => function () use ($current_localized, $supported_locales, $request) {
                    return [
                        'current' => app()->getLocale(),
                        'default' => config('app.fallback_locale'),
                        'supported' => $supported_locales,
                        'current_localized' => $current_localized,
                    ];
                },
            ], $flash, $this->doesRequestContain($request, 'login') ? [
                'has_registration' => $currentTenant->has_registration, // todo: only for specific route
            ] : []);
        }
        return array_merge(parent::share($request), $flash);
    }
}
