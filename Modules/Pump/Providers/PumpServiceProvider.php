<?php

namespace Modules\Pump\Providers;

use App\Traits\BindsModuleRequests;
use App\Traits\BindsModuleServices;
use App\Traits\UsesClassesFromModule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Services\PumpBrands\PumpBrandsSeriesInterface;
use Modules\Pump\Services\PumpBrands\PumpBrandsService;
use Modules\Pump\Services\Pumps\PumpsService;
use Modules\Pump\Services\Pumps\PumpsServiceInterface;
use Modules\Pump\Services\PumpSeries\PumpSeriesService;
use Modules\Pump\Services\PumpSeries\PumpSeriesServiceInterface;

class PumpServiceProvider extends ServiceProvider
{
    use UsesClassesFromModule, BindsModuleServices, BindsModuleRequests;

    /**
     * @var string $moduleName
     */
    protected string $moduleName = 'Pump';

    /**
     * @var string $moduleNameLower
     */
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
        $this->bindModuleServices();
//        $this->bindModuleRequests();
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

    public function services(): array
    {
        return [
            [
                'abstract' => PumpsServiceInterface::class,
                'name' => 'PumpsService',
                'default' => PumpsService::class
            ],
            [
                'abstract' => PumpSeriesServiceInterface::class,
                'name' => 'PumpSeriesService',
                'default' => PumpSeriesService::class
            ],
            [
                'abstract' => PumpBrandsSeriesInterface::class,
                'name' => 'PumpBrandsService',
                'default' => PumpBrandsService::class
            ],
        ];
    }

    public function requests(): array
    {
    }
}
