<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class HandleInertiaRequests extends Middleware
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

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param Request $request
     * @return array
     */
    public function share(Request $request): array
    {
        $supported_locales = config('app.supported_locales');
        $current_localized = [];
        foreach ($supported_locales as $locale) {
            $current_localized[$locale] = LaravelLocalization::getLocalizedURL($locale, null, [], true);
        }

        $user = Auth()->user();
        return array_merge(parent::share($request), [
            'auth' => function () use ($user) {
                return [
                    'username' => Auth::check() ? $user->first_name . " " . $user->middle_name : null,
                    'isAdmin' => Auth::check() && $user->role->id === 1
                ];
            },
            'locales' => function () use ($current_localized, $supported_locales) {
                return [
                    'current' => app()->getLocale(),
                    'supported' => $supported_locales,
                    'current_localized' => $current_localized
                ];
            },
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'warning' => $request->session()->get('warning'),
                    'info' => $request->session()->get('info'),
                    'error' => $request->session()->get('error'),
                ];
            }
        ]);
    }
}
