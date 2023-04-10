<?php

declare(strict_types=1);

namespace Tests;

use Sunaoka\Aws\Laravel\Facade\AWS;
use Sunaoka\Aws\Laravel\Provider\ServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @inerhitDoc
     */
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * @inerhitDoc
     */
    protected function getPackageAliases($app): array
    {
        return [
            'AWS' => AWS::class,
        ];
    }

    /**
     * @inerhitDoc
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('aws', [
            'version'     => 'latest',
            'region'      => 'us-east-1',
            'credentials' => false,
        ]);
    }
}
