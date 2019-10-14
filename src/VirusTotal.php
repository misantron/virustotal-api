<?php

declare(strict_types=1);

namespace Misantron\VirusTotal;

use Misantron\VirusTotal\Api\Client;
use Misantron\VirusTotal\Exception\InvalidArgumentException;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @method Resources\Url url()
 */
final class VirusTotal
{
    private const VERSION = '1.0';
    private const DEFAULT_ENDPOINT = 'https://www.virustotal.com/vtapi/v2/';

    /**
     * @var Client
     */
    private $client;

    /**
     * @param string $key
     * @param string $endpoint
     */
    private function __construct(string $key, string $endpoint)
    {
        if (trim($key) === '') {
            throw new InvalidArgumentException('API key is not defined.');
        }

        $this->createDefaultClient($key, $endpoint);
    }

    /**
     * @param string $key
     * @param string $endpoint
     *
     * @return VirusTotal
     */
    public static function create(string $key, string $endpoint = self::DEFAULT_ENDPOINT): VirusTotal
    {
        return new static($key, $endpoint);
    }

    /**
     * @param string $name
     * @param null $arguments
     *
     * @return Resources\AbstractResource
     */
    public function __call(string $name, $arguments = null): Resources\AbstractResource
    {
        $resource = __NAMESPACE__ . '\\Resources\\' . ucfirst($name);
        if (!class_exists($resource)) {
            throw new InvalidArgumentException('Unknown API resource.');
        }
        return new $resource($this->client);
    }

    /**
     * @param string $key
     * @param string $endpoint
     */
    private function createDefaultClient(string $key, string $endpoint): void
    {
        $httpClient = HttpClient::create(
            [
                'base_uri' => $endpoint,
                'headers' => [
                    'Accept' => 'application/json',
                    'User-Agent' => 'VirusTotalClient/' . self::VERSION,
                ],
            ]
        );

        $this->client = new Client($key, $httpClient);
    }
}
