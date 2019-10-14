<?php

declare(strict_types=1);

namespace Misantron\VirusTotal\Tests;

use Misantron\VirusTotal\Exception\InvalidArgumentException;
use Misantron\VirusTotal\VirusTotal;
use PHPUnit\Framework\TestCase;

class VirusTotalTest extends TestCase
{
    public function testCreateWithEmptyKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('API key is not defined.');

        VirusTotal::create('');
    }

    public function testCallUnknownResource(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown API resource.');

        VirusTotal::create(getenv('API_KEY'))->unknown();
    }

    public function testUrlResource(): void
    {
        $resource = VirusTotal::create(getenv('API_KEY'))->url();

        $this->assertSame('url', $resource->basePath());
    }
}
