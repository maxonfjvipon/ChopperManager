<?php

namespace Modules\Selection\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Http\Requests\AF\Auto\RqMakeAFAutoSelection;
use Modules\Selection\Http\Requests\AF\Auto\RqStoreAFAutoSelection;
use Modules\Selection\Http\Requests\AF\Handle\RqMakeAFHandleSelection;
use Modules\Selection\Http\Requests\AF\Handle\RqStoreAFHandleSelection;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Http\Requests\RqStoreSelection;
use Modules\Selection\Http\Requests\WS\Auto\RqMakeWSAutoSelection;
use Modules\Selection\Http\Requests\WS\Auto\RqStoreWSAutoSelection;
use Modules\Selection\Http\Requests\WS\Handle\RqMakeWSHandleSelection;
use Modules\Selection\Http\Requests\WS\Handle\RqStoreWSHandleSelection;

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
        $this->bindSelectionDependencies();
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

    public function bindSelectionDependencies()
    {
        if (request()->selection_type && request()->station_type) {
            $make = "make";
            $store = "store";
            $binder = [
                StationType::fromValue(StationType::WS)->key => [
                    SelectionType::fromValue(SelectionType::Auto)->key => [
                        $make => RqMakeWSAutoSelection::class,
                        $store => RqStoreWSAutoSelection::class
                    ],
                    SelectionType::fromValue(SelectionType::Handle)->key => [
                        $make => RqMakeWSHandleSelection::class,
                        $store => RqStoreWSHandleSelection::class,
                    ]
                ],
                StationType::fromValue(StationType::AF)->key => [
                    SelectionType::fromValue(SelectionType::Auto)->key => [
                        $make => RqMakeAFAutoSelection::class,
                        $store => RqStoreAFAutoSelection::class,
                    ],
                    SelectionType::fromValue(SelectionType::Handle)->key => [
                        $make => RqMakeAFHandleSelection::class,
                        $store => RqStoreAFHandleSelection::class
                    ]
                ],
                StationType::fromValue(StationType::Combine)->key => [
                    SelectionType::fromValue(SelectionType::Auto)->key => [
//                        'make_selection' => RqMakeWaterAutoSelection::class,
                    ],
                    SelectionType::fromValue(SelectionType::Handle)->key => [
//                        'make_selection' => RqMakeWaterAutoSelection::class,
                    ]
                ]
            ];
            $this->app->bind(RqMakeSelection::class, $binder[request()->station_type][request()->selection_type][$make]);
            $this->app->bind(RqStoreSelection::class, $binder[request()->station_type][request()->selection_type][$store]);
        }
    }
}
