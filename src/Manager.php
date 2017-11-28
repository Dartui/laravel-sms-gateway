<?php

namespace Dartui\SmsGateway;

use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class Manager
{
    /**
     * API base URL.
     *
     * @var string
     */
    public $baseUrl = 'https://smsgateway.me/api';

    /**
     * API version.
     *
     * @var string
     */
    public $version = 'v3';

    /**
     * Create manager instance.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Create full URL with base URL and API version.
     *
     * @param  string $url
     * @return string
     */
    public function buildUrl($url)
    {
        $url = implode('/', [
            $this->baseUrl,
            $this->version,
            $this->trim($url),
        ]);

        return $url;
    }

    /**
     * Trim slashes and spaces from URL.
     *
     * @param  string $url
     * @return string
     */
    protected function trim($url)
    {
        return trim($url, '\\/ ');
    }

    /**
     * Add credentials from config to request parameters.
     *
     * @param array $params
     * @throws \InvalidArgumentException
     * @return array
     */
    public function addCredentials(array $params)
    {
        $this->checkRequiredFields(['email', 'password']);

        Arr::set($params, 'email', $this->config['email']);
        Arr::set($params, 'password', $this->config['password']);

        return $params;
    }

    /**
     * Add device from config to request parameters.
     *
     * @param array $params
     * @throws \InvalidArgumentException
     * @return array
     */
    public function addDevice(array $params)
    {
        $this->checkRequiredFields(['device']);

        Arr::set($params, 'device', $this->config['device']);

        return $params;
    }

    /**
     * Check for required fields in config.
     *
     * @param  string|array $required
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function checkRequiredFields($required)
    {
        if (!is_array($required)) {
            $required = [$required];
        }

        foreach ($required as $field) {
            if (!isset($this->config[$field])) {
                throw new InvalidArgumentException("SMS Gateway {$field} not configured.");
            }
        }
    }

    /**
     * Get body content from response and decode it.
     *
     * @param  \GuzzleHttp\Psr7\Response $response
     * @return object
     */
    public function getBody(Response $response)
    {
        $content = $response->getBody()->getContents();

        return json_decode($content);
    }
}
