<?php

declare(strict_types=1);

namespace Misantron\VirusTotal\Tests\Resources;

use Misantron\VirusTotal\Api\Client;
use Misantron\VirusTotal\Resources\Url;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class UrlTest extends TestCase
{
    public function testScan(): void
    {
        $body = file_get_contents(__DIR__ . '/../Mocks/url.scan.json');
        $httpResponseMock = new MockResponse($body, ['http_method' => 'POST', 'http_code' => 200]);

        $httpClientMock = new MockHttpClient($httpResponseMock, getenv('ENDPOINT'));

        $client = new Client(getenv('API_KEY'), $httpClientMock);

        $resource = new Url($client);
        $response = $resource->scan('www.virustotal.com');
        $payload = $response->getPayload();

        $this->assertArrayHasKey('response_code', $payload);
        $this->assertArrayHasKey('verbose_msg', $payload);
        $this->assertArrayHasKey('scan_id', $payload);
        $this->assertArrayHasKey('scan_date', $payload);
        $this->assertArrayHasKey('url', $payload);
        $this->assertArrayHasKey('permalink', $payload);
    }

    public function testReport(): void
    {
        $body = file_get_contents(__DIR__ . '/../Mocks/url.report.json');
        $httpResponseMock = new MockResponse($body, ['http_method' => 'GET', 'http_code' => 200]);

        $httpClientMock = new MockHttpClient($httpResponseMock, getenv('ENDPOINT'));

        $client = new Client(getenv('API_KEY'), $httpClientMock);

        $resource = new Url($client);
        $response = $resource->report('www.virustotal.com');
        $payload = $response->getPayload();

        $this->assertArrayHasKey('response_code', $payload);
        $this->assertArrayHasKey('verbose_msg', $payload);
        $this->assertArrayHasKey('scan_id', $payload);
        $this->assertArrayHasKey('scan_date', $payload);
        $this->assertArrayHasKey('url', $payload);
        $this->assertArrayHasKey('permalink', $payload);
        $this->assertArrayHasKey('filescan_id', $payload);
        $this->assertArrayHasKey('positives', $payload);
        $this->assertArrayHasKey('positives', $payload);
        $this->assertArrayHasKey('total', $payload);
        $this->assertArrayHasKey('scans', $payload);
    }
}
