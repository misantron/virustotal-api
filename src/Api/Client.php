<?php

declare(strict_types=1);

namespace Misantron\VirusTotal\Api;

use Misantron\VirusTotal\Exception\HttpRequestException;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class Client
 *
 * @package Misantron\VirusTotal\Api
 */
final class Client
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $key;

    /**
     * @param string              $key
     * @param HttpClientInterface $httpClient
     */
    public function __construct(string $key, HttpClientInterface $httpClient)
    {
        $this->key = $key;
        $this->httpClient = $httpClient;
    }

    /**
     * @param  string $method
     * @param  string $uri
     * @param  array  $options
     *
     * @return Response
     *
     * @throws HttpRequestException
     */
    public function request(string $method, string $uri, array $options = []): Response
    {
        switch (strtolower($method)) {
            case 'post':
                $options['headers']['Content-Type'] = 'application/x-www-form-urlencoded';
                $options['body']['apikey'] = $this->key;
                break;
            case 'get':
                $options['query']['apikey'] = $this->key;
                break;
            default:
                throw new HttpRequestException('Unexpected request method.');
        }

        try {
            $response = $this->httpClient->request($method, $uri, $options);
            return Response::createWithPayload($response->toArray());
        } catch (TransportException $e) {
            if ($response->getStatusCode() === 204 && $response->getContent() === '') {
                return Response::createWithError('Daily limits exceeded.');
            }
        } catch (ClientException $e) {
            if ($response->getStatusCode() === 400) {
                return Response::createWithError('Invalid arguments provided.');
            }
            if ($response->getStatusCode() === 403) {
                return Response::createWithError('Invalid API key. Access denied.');
            }
        } catch (\Throwable $e) {
            throw new HttpRequestException('Unknown request error.');
        }
    }
}
