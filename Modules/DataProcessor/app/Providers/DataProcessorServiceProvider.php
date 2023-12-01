<?php

namespace Modules\DataProcessor\app\Providers;

use Illuminate\Support\ServiceProvider;

class DataProcessorServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'DataProcessor';

    protected string $moduleNameLower = 'dataprocessor';

    /**
     * Boot the application events.
     */
    public function boot(\Illuminate\Routing\Router $router): void
    {
        $this->registerConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->publishes([module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower.'.php')], 'config');
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
    }
}
