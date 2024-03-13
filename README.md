# AWS Service Provider for Laravel

[![Latest Stable Version](https://poser.pugx.org/sunaoka/laravel-aws-sdk-php/v/stable)](https://packagist.org/packages/sunaoka/laravel-aws-sdk-php)
[![License](https://poser.pugx.org/sunaoka/laravel-aws-sdk-php/license)](https://packagist.org/packages/sunaoka/laravel-aws-sdk-php)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/sunaoka/laravel-aws-sdk-php)](composer.json)
[![Laravel](https://img.shields.io/badge/laravel-%3E=%206.x-red)](https://laravel.com/)
[![Test](https://github.com/sunaoka/laravel-aws-sdk-php/actions/workflows/test.yml/badge.svg)](https://github.com/sunaoka/laravel-aws-sdk-php/actions/workflows/test.yml)
[![codecov](https://codecov.io/gh/sunaoka/laravel-aws-sdk-php/branch/main/graph/badge.svg?token=VW3IQRG6VG)](https://codecov.io/gh/sunaoka/laravel-aws-sdk-php)

----

## Installation

```bash
composer require sunaoka/laravel-aws-sdk-php
```

## Configurations

```bash
php artisan vendor:publish --tag=aws-config
```

The settings can be found in the generated `config/aws.php` configuration file.

```php
<?php

return [
    'credentials' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'token'  => env('AWS_SESSION_TOKEN'),
    ],
    'region'      => env('AWS_DEFAULT_REGION'),
    'version'     => env('AWS_API_VERSION', 'latest'),
    'endpoint'    => env('AWS_ENDPOINT'),

    // Override Configuration for specific services
    // 'S3' => [
    //     'use_path_style_endpoint' => false,
    // ],
];
```

## Usage

```php
$s3 = \AWS::createClient('s3');

$result = $s3->getObject([
    'Bucket' => 'Bucket',
    'Key'    => 'Key',
]);

echo $result['Body'];
```

## Testing

You may use the `AWS` facade's `fake` method to apply the mock handler.

For more information on mock handlers, please refer to the [Developer Guide](https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/guide_handlers-and-middleware.html).

```php
use Aws\Result;
use Aws\MockHandler;
use Aws\CommandInterface;
use Psr\Http\Message\RequestInterface;
use Aws\Exception\AwsException;

$mock = new MockHandler();
$mock->append(new Result(['Body' => 'foo']));
$mock->append(function (CommandInterface $cmd, RequestInterface $req) {
    return new AwsException('Mock exception', $cmd);
});

\AWS::fake($mock);

$s3 = \AWS::createClient('s3');

$result = $s3->getObject([
    'Bucket' => 'Bucket',
    'Key'    => 'Key',
]);

echo $result['Body']; // foo
```
