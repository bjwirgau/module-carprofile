<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Model;

use Magento\Framework\HTTP\Client\Curl;
use Razoyo\CarProfile\Api\CarRequestInterface;

class CarRequest implements CarRequestInterface
{
    const HTTP_OK           = 200;
    const HTTP_UNAUTHORIZED = 403;
    const HTTP_NOT_FOUND    = 404;

    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    private Curl $curlClient;

    /**
     * @param \Magento\Framework\HTTP\Client\Curl $curlClient
     */
    public function __construct(
        Curl $curlClient
    ) {
        $this->curlClient = $curlClient;
    }

    /**
     * @param string $url
     * @param string|null $token
     * @return array
     */
    public function request(string $url, ?string $token): array
    {
        try {
            $this->buildClient($token);

            $this->curlClient->get($url);
            $status = $this->curlClient->getStatus();

            return match ($status) {
                static::HTTP_OK => [
                        'errors'    => false,
                        'header'    => $this->curlClient->getHeaders(),
                        'body'      => $this->curlClient->getBody()
                    ],
                static::HTTP_UNAUTHORIZED => [
                        'errors'    => true,
                        'message'   => __('Error performing request. User is not authorized.')
                    ],
                static::HTTP_NOT_FOUND => [
                        'errors'    => true,
                        'message'   => __('Error performing request. Car not found.')
                    ],
                default => [
                        'errors'    => true,
                        'message'   => __('Error performing request.')
                    ]
            };
        } catch (\Exception $e) {
            return [
                'errors'    => true,
                'message'   => __('Error performing request.')
            ];
        }
    }

    public function buildRequestUrl(string $url, ?array $params): string
    {
        if (!$params) {
            return $url;
        }

        return $url . '?' . http_build_query($params);
    }

    private function buildClient(?string $token): void
    {
        $this->curlClient->setOptions([
            CURLOPT_RETURNTRANSFER      => true,
            CURLOPT_ENCODING            => '',
            CURLOPT_MAXREDIRS           => 10,
            CURLOPT_TIMEOUT             => 0,
            CURLOPT_FOLLOWLOCATION      => true,
            CURLOPT_HTTP_VERSION        => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER          => [
              'Content-Type: application/json'
            ]
        ]);

        if ($token) {
            $this->curlClient->setOption(CURLOPT_HTTPHEADER, [
                'Authorization: ' . 'Bearer ' . $token,
                'Content-Type: application/json'
            ]);
        }
    }
}