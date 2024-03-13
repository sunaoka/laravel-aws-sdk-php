<?php

declare(strict_types=1);

namespace Sunaoka\Aws;

use Aws\AwsClientInterface;
use Aws\MockHandler;
use Aws\MultiRegionClient;
use BadMethodCallException;
use InvalidArgumentException;

class Sdk extends \Aws\Sdk
{
    /**
     * @var MockHandler|null
     */
    protected $handler = null;

    /**
     * @inerhitDoc
     *
     * @return AwsClientInterface
     *
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     */
    public function __call($name, array $args)
    {
        $client = parent::__call($name, $args);

        $this->setHandler($client);

        return $client;
    }

    /**
     * @inerhitDoc
     *
     * @throws InvalidArgumentException
     */
    public function createClient($name, array $args = []): AwsClientInterface
    {
        $client = parent::createClient($name, $args);

        $this->setHandler($client);

        return $client;
    }

    public function fake(?MockHandler $handler = null): void
    {
        $this->handler = $handler;
    }

    protected function setHandler(AwsClientInterface $client): void
    {
        if ($this->handler !== null) {
            if ($client instanceof MultiRegionClient) {
                $client->useCustomHandler($this->handler);
            } else {
                $client->getHandlerList()->setHandler($this->handler);
            }
            $this->handler = null;
        }
    }
}
