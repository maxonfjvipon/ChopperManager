<?php

namespace Modules\Selection\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\AdminPanel\Entities\Tenant;
use Modules\AdminPanel\Entities\TenantType;
use Modules\Pump\Entities\Pump;
use Modules\PumpManager\Services\Selection\PMDoublePumpSelectionService;
use Modules\PumpManager\Services\Selection\PMPumpableSelectionService;
use Modules\PumpManager\Services\Selection\PMSelectionsService;
use Modules\PumpManager\Services\Selection\PMSinglePumpSelectionService;
use Modules\PumpProducer\Services\Selection\PPDoublePumpSelectionService;
use Modules\PumpProducer\Services\Selection\PPPumpableSelectionService;
use Modules\PumpProducer\Services\Selection\PPSelectionsService;
use Modules\PumpProducer\Services\Selection\PPSinglePumpSelectionService;
use Modules\Selection\Http\Requests\CurvesForSelectionRequest;
use Modules\Selection\Http\Requests\DoublePump\CurvesForDoublePumpSelectionRequest;
use Modules\Selection\Http\Requests\DoublePump\ExportAtOnceDoublePumpSelectionRequest;
use Modules\Selection\Http\Requests\DoublePump\MakeDoublePumpSelectionRequest;
use Modules\Selection\Http\Requests\DoublePump\StoreDoublePumpSelectionRequest;
use Modules\Selection\Http\Requests\ExportAtOnceSelectionRequest;
use Modules\Selection\Http\Requests\MakeSelectionRequest;
use Modules\Selection\Http\Requests\SinglePump\CurvesForSinglePumpSelectionRequest;
use Modules\Selection\Http\Requests\SinglePump\ExportAtOnceSinglePumpSelectionRequest;
use Modules\Selection\Http\Requests\SinglePump\MakeSinglePumpSelectionRequest;
use Modules\Selection\Http\Requests\SinglePump\StoreSinglePumpSelectionRequest;
use Modules\Selection\Http\Requests\SelectionRequest;
use Modules\Selection\Services\PumpType\DoublePumpSelectionService;
use Modules\Selection\Services\PumpType\PumpableTypeSelectionService;
use Modules\Selection\Services\PumpType\SinglePumpSelectionService;
use Modules\Selection\Services\SelectionsService;

class SelectionServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Selection';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'selection';

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
        $this->bindSelectionServices();
        $this->bindSelectionRequests();
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

    public function bindSelectionServices()
    {
        $this->app->singleton(PumpableTypeSelectionService::class, function () {
            return match (request()->pumpable_type) {
                Pump::$DOUBLE_PUMP => App::make(DoublePumpSelectionService::class),
                default => App::make(SinglePumpSelectionService::class),
            };
        });

        $this->app->bind(PMPumpableSelectionService::class, function () {
            return match (request()->pumpable_type) {
                Pump::$DOUBLE_PUMP => App::make(PMDoublePumpSelectionService::class),
                default => App::make(PMSinglePumpSelectionService::class),
            };
        });

        $this->app->bind(PPPumpableSelectionService::class, function () {
            return match (request()->pumpable_type) {
                Pump::$DOUBLE_PUMP => App::make(PPDoublePumpSelectionService::class),
                default => App::make(PPSinglePumpSelectionService::class),
            };
        });
        $this->app->bind(SelectionsService::class, function () {
            return App::make(match (Tenant::current()->type->id) {
                TenantType::$PUMPPRODUCER => PPSelectionsService::class,
                default => PMSelectionsService::class
            });
        });
    }

    public function bindSelectionRequests()
    {
        switch (request()->pumpable_type) {
            case Pump::$SINGLE_PUMP:
                $this->app->bind(MakeSelectionRequest::class, MakeSinglePumpSelectionRequest::class);
                $this->app->bind(CurvesForSelectionRequest::class, CurvesForSinglePumpSelectionRequest::class);
                $this->app->bind(ExportAtOnceSelectionRequest::class, ExportAtOnceSinglePumpSelectionRequest::class);
                $this->app->bind(SelectionRequest::class, StoreSinglePumpSelectionRequest::class);
                break;
            case Pump::$DOUBLE_PUMP:
                $this->app->bind(MakeSelectionRequest::class, MakeDoublePumpSelectionRequest::class);
                $this->app->bind(CurvesForSelectionRequest::class, CurvesForDoublePumpSelectionRequest::class);
                $this->app->bind(ExportAtOnceSelectionRequest::class, ExportAtOnceDoublePumpSelectionRequest::class);
                $this->app->bind(SelectionRequest::class, StoreDoublePumpSelectionRequest::class);
                break;
        }
    }
}
