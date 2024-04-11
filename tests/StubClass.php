<?php

declare(strict_types=1);

namespace Tests;

use Sunaoka\Aws\Laravel\Facade\AWS;

class StubClass
{
    public function get(string $bucket, string $key): string
    {
        $s3 = AWS::createS3();

        $result = $s3->getObject([
            'Bucket' => $bucket,
            'Key' => $key,
        ]);

        /** @var string */
        return $result['Body'];
    }
}
