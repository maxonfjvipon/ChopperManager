<?php

namespace Modules\Pump\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Http\Endpoints\EpLoadPumps;
use Modules\Pump\Support\LazyLoadedPumps\DPLazyLoaded;
use Modules\Pump\Support\LazyLoadedPumps\LazyLoadedPumps;
use Modules\Pump\Support\LazyLoadedPumps\SPLazyLoaded;
use Modules\Pump\Support\LoadedPumps\DPLoaded;
use Modules\Pump\Support\LoadedPumps\LoadedPumps;
use Modules\Pump\Support\LoadedPumps\SPLoaded;

class PumpServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Pump';

    protected string $moduleNameLower = 'pump';

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
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->bindPumpServices();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower.'.php'),
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
        $viewPath = resource_path('views/modules/'.$this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], ['views', $this->moduleNameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/'.$this->moduleNameLower);

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
            if (is_dir($path.'/modules/'.$this->moduleNameLower)) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }

    public function bindPumpServices()
    {
        $this->app->when(EpLoadPumps::class)
            ->needs(LoadedPumps::class)
            ->give(function () {
                return App::make(match (request()->pumpable_type) {
                    Pump::$DOUBLE_PUMP => DPLoaded::class,
                    default => SPLoaded::class
                });
            });

        $this->app->when(EpLoadPumps::class)
            ->needs(LazyLoadedPumps::class)
            ->give(function () {
                return App::make(match (request()->pumpable_type) {
                    Pump::$DOUBLE_PUMP => DPLazyLoaded::class,
                    default => SPLazyLoaded::class
                });
            });
    }
}
