<?php

declare(strict_types=1);

namespace Misantron\VirusTotal\Resources;

use Misantron\VirusTotal\Api\Response;

/**
 * Class Url
 *
 * @package Misantron\VirusTotal\Resources
 */
final class Url extends AbstractResource
{
    /**
     * @return string
     */
    public function basePath(): string
    {
        return 'url';
    }

    /**
     * @param string $url
     *
     * @return Response
     */
    public function scan(string $url): Response
    {
        return $this->client->request(
            'POST', $this->basePath() . '/scan', [
                'body' => [
                    'url' => $url,
                ],
            ]
        );
    }

    /**
     * @param string $resource
     * @param bool   $additionalInfo
     * @param bool   $scan
     *
     * @return Response
     */
    public function report(string $resource, bool $additionalInfo = false, bool $scan = false): Response
    {
        return $this->client->request(
            'GET', $this->basePath() . '/report', [
                'body' => [
                    'resource' => $resource,
                    'allinfo' => $additionalInfo,
                    'scan' => $scan ? 1 : 0,
                ],
            ]
        );
    }
}
