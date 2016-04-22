<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Psr\Http\Message\RequestInterface;
use TonicForHealth\AthenaHealth\Authenticator\BasicAuthenticator;
use TonicForHealth\AthenaHealth\Authenticator\BearerAuthenticator;
use TonicForHealth\AthenaHealth\Client;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

/**
 * Class FunctionalTestCase
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class FunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MessageFactory
     */
    protected static $messageFactory;

    /**
     * @var array
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        static::$messageFactory = MessageFactoryDiscovery::find();
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->container = [];
    }

    /**
     * @param string $expectedUri
     */
    protected function assertGetRequest($expectedUri)
    {
        /** @var RequestInterface $request */
        $request = $this->container[1]['request'];

        static::assertEquals('GET', $request->getMethod());
        static::assertEquals($expectedUri, (string) $request->getUri());
        static::assertFalse($request->hasHeader('Content-Type'));
        static::assertSame(0, $request->getBody()->getSize());
    }

    /**
     * @param string $expectedUri
     * @param string $expectedBody
     */
    protected function assertPostRequest($expectedUri, $expectedBody)
    {
        /** @var RequestInterface $request */
        $request = $this->container[1]['request'];

        static::assertEquals('POST', $request->getMethod());
        static::assertEquals($expectedUri, (string) $request->getUri());
        static::assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        static::assertEquals($expectedBody, (string) $request->getBody());
    }

    /**
     * {@inheritdoc}
     */
    protected function assertPostConditions()
    {
        static::assertCount(2, $this->container);

        /** @var RequestInterface $authRequest */
        $authRequest = $this->container[0]['request'];

        static::assertEquals('POST', $authRequest->getMethod());
        static::assertEquals('https://api.athenahealth.com/oauthpreview/token', (string) $authRequest->getUri());
        static::assertEquals('Basic U09NRS1DTElFTlQtSUQ6U09NRS1DTElFTlQtU0VDUkVU', $authRequest->getHeaderLine('Authorization'));
        static::assertEquals('application/x-www-form-urlencoded', $authRequest->getHeaderLine('Content-Type'));
        static::assertEquals('grant_type=client_credentials', (string) $authRequest->getBody());

        /** @var RequestInterface $apiRequest */
        $apiRequest = $this->container[1]['request'];

        static::assertEquals('Bearer 92df7165564a29eaaee880e1652b4fce', $apiRequest->getHeaderLine('Authorization'));

        parent::assertPostConditions();
    }

    /**
     * @param array $responses
     *
     * @return Client
     */
    protected function getClient(array $responses)
    {
        $authResponseBody = '{"access_token":"92df7165564a29eaaee880e1652b4fce","expires_in":3600}';
        array_unshift($responses, $this->getHttpResponse($authResponseBody));

        $guzzleClient = $this->getGuzzleClient($this->container, $responses);

        $basicAuthenticator = new BasicAuthenticator();
        $basicAuthenticator->setClientId('SOME-CLIENT-ID')
            ->setClientSecret('SOME-CLIENT-SECRET');

        $authHttpClient = new HttpClient($guzzleClient, static::$messageFactory);
        $authHttpClient->setAuthenticator($basicAuthenticator)
            ->setBaseUri('https://api.athenahealth.com/oauthpreview');

        $apiHttpClient = new HttpClient($guzzleClient, static::$messageFactory);
        $apiHttpClient->setAuthenticator(new BearerAuthenticator($authHttpClient))
            ->setBaseUri('https://api.athenahealth.com/preview1');

        return new Client($apiHttpClient);
    }

    /**
     * @param array $container
     * @param array $responses
     *
     * @return GuzzleAdapter
     */
    protected function getGuzzleClient(array &$container, array $responses)
    {
        $history = Middleware::history($container);

        $handlerStack = HandlerStack::create(new MockHandler($responses));
        $handlerStack->push($history);

        $guzzleHttpClient = new GuzzleClient(['handler' => $handlerStack]);

        return new GuzzleAdapter($guzzleHttpClient);
    }

    /**
     * @param string $responseBody
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getHttpResponse($responseBody)
    {
        return static::$messageFactory->createResponse(200, null, [], $responseBody);
    }
}
