<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use TonicForHealth\AthenaHealth\Authenticator\BasicAuthenticator;
use TonicForHealth\AthenaHealth\Authenticator\BearerAuthenticator;
use TonicForHealth\AthenaHealth\Client;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

/**
 * Class FunctionalTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class FunctionalTest extends \PHPUnit_Framework_TestCase
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
     * @test
     */
    public function shouldAuthAndSendGetRequest()
    {
        $expectedUri = 'https://api.athenahealth.com/preview1/endpoint?param1=1&param2=2';

        $client = $this->getClient([
            $this->getAuthResponse(),
            $this->getHttpResponse('{}'),
        ]);

        static::assertEquals([], $client->get('/endpoint', ['param1' => 1, 'param2' => 2]));
        static::assertCount(2, $this->container);

        $this->assertGetRequest($this->container[1]['request'], $expectedUri);
    }

    /**
     * @test
     */
    public function shouldAuthAndSendPostRequest()
    {
        $expectedUri = 'https://api.athenahealth.com/preview1/endpoint';
        $expectedBody = 'param1=1&param2=2';

        $client = $this->getClient([
            $this->getAuthResponse(),
            $this->getHttpResponse('{}'),
        ]);

        static::assertEquals([], $client->post('/endpoint', ['param1' => 1, 'param2' => 2]));
        static::assertCount(2, $this->container);

        $this->assertPostRequest($this->container[1]['request'], $expectedUri, $expectedBody);
    }

    /**
     * @test
     */
    public function shouldReuseAuthToken()
    {
        $expectedUri = 'https://api.athenahealth.com/preview1/endpoint';

        $client = $this->getClient([
            $this->getAuthResponse(),
            $this->getHttpResponse('{}'),
            $this->getHttpResponse('{}'),
        ]);

        static::assertEquals([], $client->get('/endpoint'));
        static::assertEquals([], $client->post('/endpoint', []));
        static::assertCount(3, $this->container);

        $this->assertGetRequest($this->container[1]['request'], $expectedUri);
        $this->assertPostRequest($this->container[2]['request'], $expectedUri, '');
    }

    /**
     * @test
     */
    public function shouldRefreshAuthToken()
    {
        $expectedUri = 'https://api.athenahealth.com/preview1/endpoint';

        $client = $this->getClient([
            $this->getAuthResponse(0),
            $this->getHttpResponse('{}'),
            $this->getAuthResponse(),
            $this->getHttpResponse('{}'),
        ]);

        static::assertEquals([], $client->get('/endpoint'));
        static::assertEquals([], $client->post('/endpoint', []));
        static::assertCount(4, $this->container);

        $this->assertGetRequest($this->container[1]['request'], $expectedUri);
        $this->assertAuthRequest($this->container[2]['request']);
        $this->assertPostRequest($this->container[3]['request'], $expectedUri, '');
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
     * {@inheritdoc}
     */
    protected function assertPostConditions()
    {
        $this->assertAuthRequest($this->container[0]['request']);

        parent::assertPostConditions();
    }

    /**
     * @param RequestInterface $request
     */
    protected function assertAuthRequest(RequestInterface $request)
    {
        static::assertEquals('POST', $request->getMethod());
        static::assertEquals('https://api.athenahealth.com/oauthpreview/token', (string) $request->getUri());
        static::assertEquals('Basic U09NRS1DTElFTlQtSUQ6U09NRS1DTElFTlQtU0VDUkVU', $request->getHeaderLine('Authorization'));
        static::assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        static::assertEquals('grant_type=client_credentials', (string) $request->getBody());
    }

    /**
     * @param RequestInterface $request
     * @param string           $expectedUri
     */
    protected function assertGetRequest(RequestInterface $request, $expectedUri)
    {
        static::assertEquals('GET', $request->getMethod());
        static::assertEquals($expectedUri, (string) $request->getUri());
        static::assertEquals('Bearer 92df7165564a29eaaee880e1652b4fce', $request->getHeaderLine('Authorization'));
        static::assertFalse($request->hasHeader('Content-Type'));
        static::assertSame(0, $request->getBody()->getSize());
    }

    /**
     * @param RequestInterface $request
     * @param string           $expectedUri
     * @param string           $expectedBody
     */
    protected function assertPostRequest(RequestInterface $request, $expectedUri, $expectedBody)
    {
        static::assertEquals('POST', $request->getMethod());
        static::assertEquals($expectedUri, (string) $request->getUri());
        static::assertEquals('Bearer 92df7165564a29eaaee880e1652b4fce', $request->getHeaderLine('Authorization'));
        static::assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        static::assertEquals($expectedBody, (string) $request->getBody());
    }

    /**
     * @param array $responses
     *
     * @return Client
     */
    protected function getClient(array $responses)
    {
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
     * @return ResponseInterface
     */
    protected function getHttpResponse($responseBody)
    {
        return static::$messageFactory->createResponse(200, null, [], $responseBody);
    }

    /**
     * @param int $expiresIn
     *
     * @return ResponseInterface
     */
    protected function getAuthResponse($expiresIn = 3600)
    {
        $responseBody = sprintf('{"access_token":"92df7165564a29eaaee880e1652b4fce","expires_in":%d}', $expiresIn);

        return $this->getHttpResponse($responseBody);
    }
}
