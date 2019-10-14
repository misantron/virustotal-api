<?php

declare(strict_types=1);

namespace Misantron\VirusTotal\Resources;

use Misantron\VirusTotal\Api\Client;

/**
 * Class AbstractResource
 *
 * @package Misantron\VirusTotal\Resources
 */
abstract class AbstractResource
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    abstract public function basePath(): string;
}
