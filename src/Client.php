<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth;

use Http\Client\Exception;
use Http\Client\Plugin\Exception\ClientErrorException;
use Http\Client\Plugin\Exception\ServerErrorException;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

/**
 * Class Client
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class Client
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * Client constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Sends a HTTP GET request.
     *
     * @param string $endpoint The API endpoint
     * @param array  $params   (Optional) The API method parameters
     *
     * @return array
     *
     * @throws ClientErrorException If response status code is a 4xx
     * @throws ServerErrorException If response status code is a 5xx
     * @throws Exception            If an error happens during processing the request
     */
    public function get($endpoint, array $params = [])
    {
        $format = (false === strpos($endpoint, '?')) ? '%s?%s' : '%s&%s';
        $uri = rtrim(sprintf($format, $endpoint, http_build_query($params)), '?&');

        $response = $this->httpClient->get($uri);

        return json_decode($response->getBody(), true);
    }

    /**
     * Sends a HTTP POST request.
     *
     * @param string $endpoint The API endpoint with replacements
     * @param array  $params   The API method parameters
     * @param array  $headers  (Optional) Additional HTTP headers
     *
     * @return array
     *
     * @throws ClientErrorException If response status code is a 4xx
     * @throws ServerErrorException If response status code is a 5xx
     * @throws Exception            If an error happens during processing the request
     */
    public function post($endpoint, array $params, array $headers = [])
    {
        $baseHeaders = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $response = $this->httpClient->post(
            $endpoint,
            array_merge($baseHeaders, $headers),
            http_build_query($params)
        );

        return json_decode($response->getBody(), true);
    }
}
