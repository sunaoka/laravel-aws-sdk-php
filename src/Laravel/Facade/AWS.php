<?php

declare(strict_types=1);

namespace Sunaoka\Aws\Laravel\Facade;

use Illuminate\Support\Facades\Facade;
use Sunaoka\Aws\Sdk;

/**
 * @mixin Sdk
 */
class AWS extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return Sdk::class;
    }
}
