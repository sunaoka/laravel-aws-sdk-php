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
     * @var MockHandler|array<class-string<AwsClientInterface>, MockHandler>|null
     */
    protected $handler = null;

    /**
     * @param  string  $name
     * @param  mixed[]  $args
     * @return AwsClientInterface
     *
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     */
    public function __call($name, array $args)
    {
        /** @var AwsClientInterface $client */
        $client = parent::__call($name, $args);

        $this->setHandler($client);

        return $client;
    }

    /**
     * @param  string  $name
     * @param  mixed[]  $args
     *
     * @throws InvalidArgumentException
     */
    public function createClient($name, array $args = []): AwsClientInterface
    {
        $client = parent::createClient($name, $args);

        $this->setHandler($client);

        return $client;
    }

    /**
     * @param  MockHandler|array<class-string<AwsClientInterface>, MockHandler>|null  $handler
     */
    public function fake($handler = null): void
    {
        $this->handler = $handler;
    }

    protected function setHandler(AwsClientInterface $client): void
    {
        if ($this->handler === null) {
            return;
        }

        if ($this->handler instanceof MockHandler) {
            if ($client instanceof MultiRegionClient) {
                $client->useCustomHandler($this->handler);
            } else {
                $client->getHandlerList()->setHandler($this->handler);
            }
            $this->handler = null;

            return;
        }

        $clientClass = get_class($client);
        if (isset($this->handler[$clientClass])) {
            if ($client instanceof MultiRegionClient) {
                $client->useCustomHandler($this->handler[$clientClass]);
            } else {
                $client->getHandlerList()->setHandler($this->handler[$clientClass]);
            }
            unset($this->handler[$clientClass]);
        }
    }
}
