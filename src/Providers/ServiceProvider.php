<?php

namespace Gunther\Providers;

use Gunther\Commands\UpdateTranslations;
use Gunther\Services\Publisher;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application.
     *
     * @return void
     */
    public function boot(): void
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
    public function register(): void
    {
        $app = $this->app;
        $app->bind('gunther.publisher', function () use ($app) {
            return new Publisher($app->make('config'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return ['gunther.publisher'];
    }

    /**
     * Register and publish configuration files.
     *
     * @return void
     */
    protected function registerAndPublishConfigurations(): void
    {
        $configFile = __DIR__.'/../../config/gunther.php';

        $this->publishes([$configFile => config_path('gunther.php')], 'config');

        $this->mergeConfigFrom($configFile, 'gunther');
    }
}
