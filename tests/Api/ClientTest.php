<?php

declare(strict_types=1);

namespace Misantron\VirusTotal\Tests\Api;

use Misantron\VirusTotal\Api\Client;
use Misantron\VirusTotal\Exception\HttpRequestException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ClientTest extends TestCase
{
    public function testRequestWithNotAvailableHttpMethod(): void
    {
        $this->expectException(HttpRequestException::class);
        $this->expectExceptionMessage('Unexpected request method.');

        $client = new Client(getenv('API_KEY'), new MockHttpClient());
        $client->request('PUT', 'foo');
    }

    public function testRequestWithEmptyResponse(): void
    {
        $method = 'GET';
        $body = '';
        $httpResponseMock = new MockResponse($body, ['http_method' => $method, 'http_code' => 204]);

        $httpClientMock = new MockHttpClient($httpResponseMock, getenv('ENDPOINT'));

        $client = new Client(getenv('API_KEY'), $httpClientMock);
        $response = $client->request($method, 'foo');

        $this->assertTrue($response->hasError());
        $this->assertSame('Daily limits exceeded.', $response->getError());
    }

    public function testRequestWithWrongArguments(): void
    {
        $method = 'POST';
        $body = json_encode([
            'foo' => 'bar',
        ]);
        $httpResponseMock = new MockResponse($body, ['http_method' => $method, 'http_code' => 400]);

        $httpClientMock = new MockHttpClient($httpResponseMock, getenv('ENDPOINT'));

        $client = new Client(getenv('API_KEY'), $httpClientMock);
        $response = $client->request($method, 'foo');

        $this->assertTrue($response->hasError());
        $this->assertSame('Invalid arguments provided.', $response->getError());
    }

    public function testRequestWithInvalidApiKey(): void
    {
        $method = 'GET';
        $body = json_encode([
            'foo' => 'bar',
        ]);
        $httpResponseMock = new MockResponse($body, ['http_method' => $method, 'http_code' => 403]);

        $httpClientMock = new MockHttpClient($httpResponseMock, getenv('ENDPOINT'));

        $client = new Client('invalid.api.key', $httpClientMock);
        $response = $client->request($method, 'foo');

        $this->assertTrue($response->hasError());
        $this->assertSame('Invalid API key. Access denied.', $response->getError());
    }

    public function testRequestWithUnknownError(): void
    {
        $this->expectException(HttpRequestException::class);
        $this->expectExceptionMessage('Unknown request error.');

        $method = 'POST';
        $body = 'Internal server error';
        $httpResponseMock = new MockResponse($body, ['http_method' => $method, 'http_code' => 500]);

        $httpClientMock = new MockHttpClient($httpResponseMock, getenv('ENDPOINT'));

        $client = new Client(getenv('API_KEY'), $httpClientMock);
        $client->request($method, 'foo');
    }
}
