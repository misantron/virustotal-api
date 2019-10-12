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
    protected function basePath(): string
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
            'post', $this->basePath() . '/scan', [
                'body' => [
                    'url' => $url,
                ],
            ]
        );
    }
}
