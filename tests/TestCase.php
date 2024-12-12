<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Application;
use Sunaoka\Aws\Laravel\Facade\AWS;
use Sunaoka\Aws\Laravel\Provider\ServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     * @return array<string, class-string>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'AWS' => AWS::class,
        ];
    }

    /**
     * @param  Application  $app
     *
     * @throws BindingResolutionException
     */
    protected function defineEnvironment($app): void
    {
        // @phpstan-ignore argument.type (Illuminate\Config\Repository)
        tap($app->make('config'), static function (Repository $config) {
            $config->set('aws', [
                'version' => 'latest',
                'region' => 'us-east-1',
                'credentials' => false,
            ]);

            return $config;
        });
    }
}
