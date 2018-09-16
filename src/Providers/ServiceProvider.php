<?php

namespace Gunther\Providers;

use Gunther\Services\Publisher;
use Gunther\Commands\UpdateTranslations;
use Illuminate\Config\Repository as Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerAndPublishConfigurations();

        if ($this->app->runningInConsole()) {
            $this->commands([
                UpdateTranslations::class,
            ]);
        }
    }

    /**
     * Register the package.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;
        $app->bind('gunther.publisher', function () use ($app) {
            return new Publisher($app->make('config'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['gunther.publisher'];
    }

    /**
     * Register and publish configuration files.
     *
     * @return void
     */
    protected function registerAndPublishConfigurations()
    {
        $configFile = __DIR__.'/../../config/gunther.php';

        $this->publishes([$configFile => config_path('gunther.php')], 'config');

        $this->mergeConfigFrom($configFile, 'gunther');
    }
}
