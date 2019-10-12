<?php

declare(strict_types=1);

namespace Misantron\VirusTotal\Api;

final class Response
{
    /**
     * @var string
     */
    private $error;

    /**
     * @var array
     */
    private $payload;

    private function __construct(array $payload = [], string $error = '')
    {
        $this->payload = $payload;
        $this->error = $error;
    }

    public static function createWithPayload(array $payload): Response
    {
        return new static($payload);
    }

    public static function createWithError(string $error): Response
    {
        return new static([], $error);
    }

    public function hasError(): bool
    {
        return $this->error !== '';
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getPayload(): array
    {
        return $this->payload ?? [];
    }
}
