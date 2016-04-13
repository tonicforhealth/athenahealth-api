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
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use TonicForHealth\AthenaHealth\Authenticator\BearerAuthenticator;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

/**
 * Class BearerAuthenticatorTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class BearerAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
    const OAUTH_PREVIEW_URI = '/oauthpreview/token';

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var BearerAuthenticator
     */
    protected $authenticator;

    /**
     * @test
     * @small
     */
    public function shouldAuthenticate()
    {
        $accessToken = md5(uniqid('accessToken#', false));

        $responses = [
            new Response(200, [], sprintf('{"access_token":"%s","expires_in":3600}', $accessToken)),
            new Response(),
        ];

        $history = [];

        $httpClient = $this->createHttpClient($responses, $history);
        $this->authenticator->authenticate($httpClient)->get('/');

        static::assertCount(2, $history);

        $this->assertAuthRequest($history[0]['request']);
        $this->assertDataReques($history[1]['request'], $accessToken);
    }

    /**
     * @test
     * @small
     */
    public function shouldReuseToken()
    {
        $accessToken = md5(uniqid('accessToken#', false));

        $responses = [
            new Response(200, [], sprintf('{"access_token":"%s","expires_in":3600}', $accessToken)),
            new Response(),
            new Response(),
        ];

        $history = [];

        $httpClient = $this->createHttpClient($responses, $history);
        $this->authenticator->authenticate($httpClient)->get('/');
        $this->authenticator->authenticate($httpClient)->get('/');

        static::assertCount(3, $history);

        $this->assertAuthRequest($history[0]['request']);
        $this->assertDataReques($history[1]['request'], $accessToken);
        $this->assertDataReques($history[2]['request'], $accessToken);
    }

    /**
     * @test
     * @small
     */
    public function shouldRefreshToken()
    {
        $accessToken1 = md5(uniqid('accessToken#', false));
        $accessToken2 = md5(uniqid('accessToken#', false));

        $responses = [
            new Response(200, [], sprintf('{"access_token":"%s","expires_in":0}', $accessToken1)),
            new Response(),
            new Response(200, [], sprintf('{"access_token":"%s","expires_in":3600}', $accessToken2)),
            new Response(),
        ];

        $history = [];

        $httpClient = $this->createHttpClient($responses, $history);
        $this->authenticator->authenticate($httpClient)->get('/');
        $this->authenticator->authenticate($httpClient)->get('/');

        static::assertCount(4, $history);

        $this->assertAuthRequest($history[0]['request']);
        $this->assertDataReques($history[1]['request'], $accessToken1);
        $this->assertAuthRequest($history[2]['request']);
        $this->assertDataReques($history[3]['request'], $accessToken2);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->clientId = uniqid('clientId#', false);
        $this->clientSecret = uniqid('clientSecret#', false);

        $this->authenticator = new BearerAuthenticator();
        $this->authenticator->setClientId($this->clientId)
            ->setClientSecret($this->clientSecret)
            ->setAuthUri(static::OAUTH_PREVIEW_URI);
    }

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
     * @param Request $request
     */
    protected function assertAuthRequest(Request $request)
    {
        $basicAuthHeader = sprintf('Basic %s', base64_encode(sprintf('%s:%s', $this->clientId, $this->clientSecret)));

        static::assertEquals('POST', $request->getMethod());
        static::assertEquals(static::OAUTH_PREVIEW_URI, (string) $request->getUri());
        static::assertEquals('grant_type=client_credentials', (string) $request->getBody());
        static::assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        static::assertEquals($basicAuthHeader, $request->getHeaderLine('Authorization'));
    }

    /**
     * @param Request $request
     * @param string  $accessToken
     */
    protected function assertDataReques(Request $request, $accessToken)
    {
        $bearerAuthHeader = sprintf('Bearer %s', $accessToken);

        static::assertEquals($bearerAuthHeader, $request->getHeaderLine('Authorization'));
    }
}
