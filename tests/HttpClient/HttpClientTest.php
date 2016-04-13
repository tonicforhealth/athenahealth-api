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
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Message\Authentication;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Client\HttpClient as HttpClientInterface;
use Http\Message\MessageFactory as MessageFactoryInterface;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

/**
 * Class HttpClientTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class HttpClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @small
     */
    public function shouldConstructSameObjects()
    {
        $clientWithDiscovery = new HttpClient();

        $guzzleHttpAdapter = new GuzzleAdapter();
        $guzzleMessageFactory = new GuzzleMessageFactory();

        $clientWithoutDiscovery = new HttpClient($guzzleHttpAdapter, $guzzleMessageFactory);

        static::assertEquals($clientWithDiscovery, $clientWithoutDiscovery);
    }

    /**
     * @test
     * @small
     */
    public function shouldUseConstructorParams()
    {
        $method = 'POST';
        $uri = 'http://localhost/';
        $headers = ['X-Some-Header' => microtime(true)];
        $body = http_build_query(['param' => 'value']);

        $request = new Request($method, $uri, $headers, $body);
        $response = new Response();

        /** @var \PHPUnit_Framework_MockObject_MockObject|HttpClientInterface $httpClientMock */
        $httpClientMock = $this->getMockForAbstractClass(HttpClientInterface::class);
        $httpClientMock->expects(static::once())
            ->method('sendRequest')
            ->with($request)
            ->willReturn($response);

        /** @var \PHPUnit_Framework_MockObject_MockObject|MessageFactoryInterface $messageFactoryMock */
        $messageFactoryMock = $this->getMockForAbstractClass(MessageFactoryInterface::class, ['createRequest']);
        $messageFactoryMock->expects(static::once())
            ->method('createRequest')
            ->with($method, $uri, $headers, $body)
            ->willReturn($request);

        $httpClient = new HttpClient($httpClientMock, $messageFactoryMock);

        static::assertSame($response, $httpClient->send($method, $uri, $headers, $body));
    }

    /**
     * @test
     * @small
     */
    public function shouldSendRequestWithoutAuthentication()
    {
        $request = new Request('GET', '/');
        $response = new Response();

        $httpClient = $this->createHttpClient([$response]);

        static::assertSame($response, $httpClient->sendRequest($request));
    }

    /**
     * @test
     * @small
     */
    public function shouldSendRequestWithAuthentication()
    {
        $request = new Request('GET', '/');
        $response = new Response();

        $httpClient = $this->createHttpClient([$response]);

        /** @var \PHPUnit_Framework_MockObject_MockObject|Authentication $authenticationMock */
        $authenticationMock = $this->getMockForAbstractClass(Authentication::class, ['authenticate']);
        $authenticationMock->expects(static::once())
            ->method('authenticate')
            ->with($request)
            ->willReturn($request);

        $httpClient->setAuthentication($authenticationMock);

        static::assertSame($response, $httpClient->sendRequest($request));
    }

    /**
     * @param Response[] $responses
     *
     * @return HttpClient
     */
    protected function createHttpClient(array $responses)
    {
        $handlerStack = HandlerStack::create(new MockHandler($responses));
        $guzzleHttpClient = new GuzzleClient(['handler' => $handlerStack]);
        $guzzleHttpAdapter = new GuzzleAdapter($guzzleHttpClient);

        return new HttpClient($guzzleHttpAdapter);
    }
}
