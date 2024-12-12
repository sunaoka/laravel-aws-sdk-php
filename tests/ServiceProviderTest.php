<?php

declare(strict_types=1);

namespace Tests;

use Aws\DynamoDb\DynamoDbClient;
use Aws\MockHandler;
use Aws\Result;
use Aws\S3\S3Client;
use Aws\S3\S3MultiRegionClient;
use Sunaoka\Aws\Laravel\Facade\AWS;

class ServiceProviderTest extends TestCase
{
    public function test_facade(): void
    {
        $actual = AWS::createClient('s3');

        self::assertInstanceOf(S3Client::class, $actual);
    }

    public function test_create_client_mock_handler(): void
    {
        AWS::fake(new MockHandler([new Result(['Body' => __METHOD__])]));

        $actual = AWS::createClient('s3');

        self::assertInstanceOf(S3Client::class, $actual);

        $actual = $actual->getObject([
            'Bucket' => 'Bucket',
            'Key' => 'Key',
        ]);

        self::assertSame(__METHOD__, $actual['Body']);
    }

    public function test_create_multi_region_client_mock_handler(): void
    {
        AWS::fake(new MockHandler([new Result(['Body' => __METHOD__])]));

        $actual = AWS::createMultiRegionS3();

        self::assertInstanceOf(S3MultiRegionClient::class, $actual);

        $actual = $actual->getObject([
            'Bucket' => 'Bucket',
            'Key' => 'Key',
        ]);

        self::assertSame(__METHOD__, $actual['Body']);
    }

    public function test_named_client_mock_handler(): void
    {
        AWS::fake(new MockHandler([new Result(['Body' => __METHOD__])]));

        $actual = AWS::createS3();

        self::assertInstanceOf(S3Client::class, $actual);

        $actual = $actual->getObject([
            'Bucket' => 'Bucket',
            'Key' => 'Key',
        ]);

        self::assertSame(__METHOD__, $actual['Body']);
    }

    public function test_stub_class_mock_handler(): void
    {
        AWS::fake(new MockHandler([new Result(['Body' => __METHOD__])]));

        $class = new StubClass;
        $actual = $class->get('Bucket', 'Key');

        self::assertSame(__METHOD__, $actual);
    }

    public function test_multiple_mock_handler(): void
    {
        AWS::fake([
            S3Client::class => new MockHandler([
                new Result(['Body' => __METHOD__]),
            ]),
            S3MultiRegionClient::class => new MockHandler([
                new Result(['Body' => __METHOD__]),
            ]),
            DynamoDbClient::class => new MockHandler([
                new Result(['TableNames' => ['Table1', 'Table2', 'Table3']]),
            ]),
        ]);

        $s3 = AWS::createS3();
        $actual = $s3->getObject([
            'Bucket' => 'Bucket',
            'Key' => 'Key',
        ]);

        self::assertSame(__METHOD__, $actual['Body']);

        $s3 = AWS::createMultiRegionS3();
        $actual = $s3->getObject([
            'Bucket' => 'Bucket',
            'Key' => 'Key',
        ]);

        self::assertSame(__METHOD__, $actual['Body']);

        $dynamoDb = AWS::createDynamoDb();
        $actual = $dynamoDb->listTables();

        self::assertSame(['Table1', 'Table2', 'Table3'], $actual['TableNames']);
    }
}
