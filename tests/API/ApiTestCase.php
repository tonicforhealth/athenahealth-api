<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\API;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use TonicForHealth\AthenaHealth\Authenticator\VoidAuthenticator;
use TonicForHealth\AthenaHealth\Client;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

/**
 * Class ApiTestCase
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class ApiTestCase extends \PHPUnit_Framework_TestCase
{
    const API_BASE_URI = 'http://localhost/';
    const API_PRACTICE_ID = 195900;

    /**
     * @param Response[] $responses
     * @param array      $container
     *
     * @return HttpClient
     */
    protected function createHttpClient(array $responses, array &$container)
    {
        $history = Middleware::history($container);

        $handlerStack = HandlerStack::create(new MockHandler($responses));
        $handlerStack->push($history);

        $guzzleHttpClient = new GuzzleClient(['handler' => $handlerStack]);
        $guzzleHttpAdapter = new GuzzleAdapter($guzzleHttpClient);

        return new HttpClient($guzzleHttpAdapter);
    }

    /**
     * @param Response[] $responses
     * @param array      $container
     *
     * @return Client
     */
    protected function createApiClient(array $responses, array &$container)
    {
        $httpClient = $this->createHttpClient($responses, $container);

        $apiClient = new Client($httpClient, new VoidAuthenticator());
        $apiClient->setBaseUri(static::API_BASE_URI)->setPracticeId(static::API_PRACTICE_ID);

        return $apiClient;
    }
}
