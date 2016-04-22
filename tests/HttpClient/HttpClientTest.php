<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use TonicForHealth\AthenaHealth\Authenticator\AuthenticatorInterface;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;
use TonicForHealth\AthenaHealth\Tests\Authenticator\DataFixtures\VoidAuthentication;

/**
 * Class HttpClientTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class HttpClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->container = [];
    }

    /**
     * @test
     * @dataProvider providerSend
     *
     * @param string $baseUri
     * @param string $uri
     * @param string $expectedUri
     */
    public function shouldSend($baseUri, $uri, $expectedUri)
    {
        $method = uniqid('method#', false);
        $headerName = uniqid('headerName#', false);
        $headerValue = uniqid('headerValue#', false);
        $body = uniqid('body#', false);

        $response = new Response();

        $httpClient = $this->createHttpClient([$response]);
        $httpClient->setBaseUri($baseUri);

        static::assertSame($response, $httpClient->send($method, $uri, [$headerName => $headerValue], $body));
        static::assertCount(1, $this->container);
        static::assertNull($this->container[0]['error']);

        /** @var Request $request */
        $request = $this->container[0]['request'];

        static::assertEquals(strtoupper($method), $request->getMethod());
        static::assertEquals($expectedUri, (string)$request->getUri());
        static::assertEquals($headerValue, $request->getHeaderLine($headerName));
        static::assertEquals($body, $request->getBody());
    }

    /**
     * @see shouldSend
     *
     * @return array
     */
    public function providerSend()
    {
        return [
            ['', '/some-path', '/some-path'],
            ['http://localhost', '/some-path', 'http://localhost/some-path'],
            ['http://localhost/', '/some-path', 'http://localhost/some-path'],
        ];
    }

    /**
     * @param Response[] $responses
     *
     * @return HttpClient
     */
    protected function createHttpClient(array $responses)
    {
        $history = Middleware::history($this->container);

        $handlerStack = HandlerStack::create(new MockHandler($responses));
        $handlerStack->push($history);

        $guzzleHttpClient = new GuzzleClient(['handler' => $handlerStack]);
        $guzzleHttpAdapter = new GuzzleAdapter($guzzleHttpClient);

        /** @var \PHPUnit_Framework_MockObject_MockObject|AuthenticatorInterface $authenticator */
        $authenticator = $this->getMockForAbstractClass(AuthenticatorInterface::class);
        $authenticator->expects(static::once())
            ->method('getAuthentication')
            ->willReturn(new VoidAuthentication());

        $httpClient = new HttpClient($guzzleHttpAdapter, new GuzzleMessageFactory());
        $httpClient->setAuthenticator($authenticator);

        return $httpClient;
    }
}
