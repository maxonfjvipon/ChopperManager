<?php

namespace Modules\User\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\AdminPanel\Entities\Tenant;
use Modules\AdminPanel\Entities\TenantType;
use Modules\PumpManager\Http\Requests\PMCreateUserRequest;
use Modules\PumpManager\Http\Requests\PMUpdateProfileRequest;
use Modules\PumpManager\Http\Requests\PMUpdateUserRequest;
use Modules\PumpManager\Services\PMUsersService;
use Modules\PumpProducer\Http\Requests\PPCreateUserRequest;
use Modules\PumpProducer\Http\Requests\PPUpdateProfileRequest;
use Modules\PumpProducer\Http\Requests\PPUpdateUserRequest;
use Modules\PumpProducer\Services\PPUsersService;
use Modules\User\Contracts\UsersServiceContract;
use Modules\User\Http\Controllers\UsersController;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateProfileRequest;
use Modules\User\Http\Requests\UpdateUserRequest;

class UserServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'User';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'user';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->bindServices();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }

    public function bindServices()
    {
        if (Tenant::checkCurrent())
            switch (Tenant::current()->type->id) {
                case TenantType::$PUMPPRODUCER:
                    $this->app->when(UsersController::class)
                        ->needs(UsersServiceContract::class)
                        ->give(PPUsersService::class);
                    $this->app->bind(CreateUserRequest::class, PPCreateUserRequest::class);
                    $this->app->bind(UpdateUserRequest::class, PPUpdateUserRequest::class);
                    $this->app->bind(UpdateProfileRequest::class, PPUpdateProfileRequest::class);
                    break;
                default:
                    $this->app->when(UsersController::class)
                        ->needs(UsersServiceContract::class)
                        ->give(PMUsersService::class);
                    $this->app->bind(CreateUserRequest::class, PMCreateUserRequest::class);
                    $this->app->bind(UpdateUserRequest::class, PMUpdateUserRequest::class);
                    $this->app->bind(UpdateProfileRequest::class, PMUpdateProfileRequest::class);
                    break;
            }
    }
}
