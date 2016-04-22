<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\Authenticator;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication\Bearer;
use Http\Message\MessageFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use TonicForHealth\AthenaHealth\Authenticator\AuthenticatorInterface;
use TonicForHealth\AthenaHealth\Authenticator\BearerAuthenticator;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;
use TonicForHealth\AthenaHealth\Tests\Authenticator\DataFixtures\VoidAuthentication;

/**
 * Class BearerAuthenticatorTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class BearerAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MessageFactory
     */
    protected static $messageFactory;

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
    public function shouldAuthenticate()
    {
        $accessToken = md5(uniqid('accessToken#', false));
        $responseBody = sprintf('{"access_token":"%s","expires_in":3600}', $accessToken);

        $responses = [
            static::$messageFactory->createResponse(200, null, [], $responseBody),
        ];

        $history = [];
        $request = static::$messageFactory->createRequest('GET', '/');

        $httpClient = $this->createHttpClient($responses, $history);
        $httpClient->setAuthenticator($this->getAuthenticator());

        $authenticator = new BearerAuthenticator($httpClient);
        $authentication = $authenticator->getAuthentication();

        static::assertInstanceOf(Bearer::class, $authentication);

        $headerLine = $authentication->authenticate($request)->getHeaderLine('Authorization');
        static::assertEquals(sprintf('Bearer %s', $accessToken), $headerLine);

        static::assertCount(1, $history);
        static::assertNull($history[0]['error']);

        /** @var RequestInterface $internalRequest */
        $internalRequest = $history[0]['request'];

        static::assertEquals('POST', $internalRequest->getMethod());
        static::assertEquals('/token', (string) $internalRequest->getUri());
        static::assertEquals('application/x-www-form-urlencoded', $internalRequest->getHeaderLine('Content-Type'));
        static::assertEquals('grant_type=client_credentials', (string) $internalRequest->getBody());
    }

    /**
     * @test
     */
    public function shouldReuseToken()
    {
        $accessToken = md5(uniqid('accessToken#', false));
        $responseBody = sprintf('{"access_token":"%s","expires_in":3600}', $accessToken);

        $responses = [
            static::$messageFactory->createResponse(200, null, [], $responseBody),
        ];

        $history = [];
        $request = static::$messageFactory->createRequest('GET', '/');

        $httpClient = $this->createHttpClient($responses, $history);
        $httpClient->setAuthenticator($this->getAuthenticator());

        $authenticator = new BearerAuthenticator($httpClient);

        for ($i = 0; $i < 2; $i++) {
            $authentication = $authenticator->getAuthentication();

            $headerLine = $authentication->authenticate($request)->getHeaderLine('Authorization');
            static::assertEquals(sprintf('Bearer %s', $accessToken), $headerLine);
        }

        static::assertCount(1, $history);
    }

    /**
     * @test
     */
    public function shouldRefreshToken()
    {
        $accessToken = md5(uniqid('accessToken#', false));
        $responseBody = sprintf('{"access_token":"%s","expires_in":0}', $accessToken);

        $responses = [
            static::$messageFactory->createResponse(200, null, [], $responseBody),
            static::$messageFactory->createResponse(200, null, [], $responseBody),
        ];

        $history = [];
        $request = static::$messageFactory->createRequest('GET', '/');

        $httpClient = $this->createHttpClient($responses, $history);
        $httpClient->setAuthenticator($this->getAuthenticator(2));

        $authenticator = new BearerAuthenticator($httpClient);

        for ($i = 0; $i < 2; $i++) {
            $authentication = $authenticator->getAuthentication();

            $headerLine = $authentication->authenticate($request)->getHeaderLine('Authorization');
            static::assertEquals(sprintf('Bearer %s', $accessToken), $headerLine);
        }

        static::assertCount(2, $history);
    }

    /**
     * @param ResponseInterface[] $responses
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

        return new HttpClient($guzzleHttpAdapter, static::$messageFactory);
    }

    /**
     * @param int $count
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|AuthenticatorInterface
     */
    protected function getAuthenticator($count = 1)
    {
        $authenticator = $this->getMockForAbstractClass(AuthenticatorInterface::class);
        $authenticator->expects(static::exactly($count))
            ->method('getAuthentication')
            ->willReturn(new VoidAuthentication());

        return $authenticator;
    }
}
