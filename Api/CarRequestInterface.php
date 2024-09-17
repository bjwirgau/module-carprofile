<?php

namespace Razoyo\CarProfile\Api;

interface CarRequestInterface
{
    /**
     * @param string $url
     * @param string|null $token
     * @return array
     */
    public function request(string $url, ?string $token): array;

    /**
     * @param string $url
     * @param array|null $params
     * @return string
     */
    public function buildRequestUrl(string $url, ?array $params): string;
}