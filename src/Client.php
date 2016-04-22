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
use TonicForHealth\AthenaHealth\Api\PracticeInterface;
use TonicForHealth\AthenaHealth\ApiEndpoint\PracticeEndpoint;
use TonicForHealth\AthenaHealth\ApiMethod\ApiMethodInterface;
use TonicForHealth\AthenaHealth\ApiMethod\Practice\PracticeInfoMethod;
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
     * Returns a practice endpoint.
     *
     * @param int $practiceId
     *
     * @return PracticeInterface
     */
    public function practice($practiceId)
    {
        $practice = new PracticeEndpoint($this);
        $practice->setPracticeId($practiceId);

        return $practice;
    }

    /**
     * Returns a list of all practices that an API user has access to.
     *
     * @param int|null $limit  (Optional) Number of entries to return (default 1500, max 5000)
     * @param int|null $offset (Optional) Starting point of entries; 0-indexed
     *
     * @return array
     *
     * @throws ClientErrorException If response status code is a 4xx
     * @throws ServerErrorException If response status code is a 5xx
     * @throws Exception            If an error happens during processing the request
     */
    public function practiceInfo($limit = null, $offset = null)
    {
        $practiceInfo = new PracticeInfoMethod();

        if (null !== $limit) {
            $practiceInfo->setLimit($limit);
        }

        if (null !== $offset) {
            $practiceInfo->setOffset($offset);
        }

        return $this->sendRequest($practiceInfo);
    }

    /**
     * Sends a predefined API request.
     *
     * @param ApiMethodInterface $apiMethod
     *
     * @return array
     *
     * @throws ClientErrorException If response status code is a 4xx
     * @throws ServerErrorException If response status code is a 5xx
     * @throws Exception            If an error happens during processing the request
     */
    public function sendRequest(ApiMethodInterface $apiMethod)
    {
        $response = $this->httpClient->send(
            $apiMethod->getRequestMethod(),
            $apiMethod->getRequestUri(),
            $apiMethod->getRequestHeaders(),
            $apiMethod->getRequestBody()
        );

        return json_decode($response->getBody(), true);
    }
}
