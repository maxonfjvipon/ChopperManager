<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use Throwable;

class Handler extends ExceptionHandler
{
    use UsesTenantModel;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);
        $currentTenant = $this->getTenantModel()::current();

        // TODO: fix for production
        if (!app()->environment(['production', 'testing']) && in_array($response->status(), [500, 503, 404, 403])) {
            return Inertia::render('Core::Error', [
                'status' => $response->status(),
                'title' => $currentTenant->name ?? "Pump Manager"
            ])
                ->toResponse($request)
                ->setStatusCode($response->status());
        } else if ($response->status() === 419) {
            return back()->with([
                'message' => 'The page expired, please try again.',
            ]);
        }

        return $response;
    }
}
