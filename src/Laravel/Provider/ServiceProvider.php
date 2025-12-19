<?php

declare(strict_types=1);

namespace Sunaoka\Aws\Laravel\Provider;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Sunaoka\Aws\Sdk;

class ServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__, 3).'/config/aws.php',
            'aws'
        );

        $this->app->singleton(Sdk::class, function (Application $app) {
            $config = $app->make('config')->get('aws');  // @phpstan-ignore method.nonObject (Illuminate\Config\Repository)

            return new Sdk($config);  // @phpstan-ignore argument.type ($config is array)
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [dirname(__DIR__, 3).'/config/aws.php' => $this->app->configPath('aws.php')],
                'aws-config'
            );
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides(): array
    {
        return [Sdk::class];
    }
}
