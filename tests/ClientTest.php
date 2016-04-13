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

use GuzzleHttp\Psr7\Response;
use TonicForHealth\AthenaHealth\API\Appointments;
use TonicForHealth\AthenaHealth\API\Patients;
use TonicForHealth\AthenaHealth\API\Practice;
use TonicForHealth\AthenaHealth\Authenticator\AuthenticatorInterface;
use TonicForHealth\AthenaHealth\Authenticator\VoidAuthenticator;
use TonicForHealth\AthenaHealth\Client;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

/**
 * Class ClientTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @small
     * @dataProvider providerRequestUri
     *
     * @param string $baseUri
     * @param string $uri
     * @param string $expectedUri
     */
    public function shouldAuthenticateAndGet($baseUri, $uri, $expectedUri)
    {
        $headers = ['X-Some-Header' => microtime(true)];
        $response = new Response();

        $httpClientMock = $this->getHttpClientMock(['get']);
        $httpClientMock->expects(static::once())
            ->method('get')
            ->with($expectedUri, $headers)
            ->willReturn($response);

        $authenticatorMock = $this->getAuthenticatorMock($httpClientMock);

        $apiClient = new Client($httpClientMock, $authenticatorMock);
        $apiClient->setBaseUri($baseUri);

        static::assertSame($response, $apiClient->get($uri, $headers));
    }

    /**
     * @test
     * @small
     * @dataProvider providerRequestUri
     *
     * @param string $baseUri
     * @param string $uri
     * @param string $expectedUri
     */
    public function shouldAuthenticateAndPost($baseUri, $uri, $expectedUri)
    {
        $headers = ['X-Some-Header' => microtime(true)];
        $body = md5(microtime(true));
        $response = new Response();

        $httpClientMock = $this->getHttpClientMock(['post']);
        $httpClientMock->expects(static::once())
            ->method('post')
            ->with($expectedUri, $headers, $body)
            ->willReturn($response);

        $authenticatorMock = $this->getAuthenticatorMock($httpClientMock);

        $apiClient = new Client($httpClientMock, $authenticatorMock);
        $apiClient->setBaseUri($baseUri);

        static::assertSame($response, $apiClient->post($uri, $headers, $body));
    }

    /**
     * @see shouldAuthenticateAndGet
     * @see shouldAuthenticateAndPost
     *
     * @return array
     */
    public function providerRequestUri()
    {
        return [
            ['', '/some-path', '/some-path'],
            ['http://localhost', '/some-path', 'http://localhost/some-path'],
            ['http://localhost/', '/some-path', 'http://localhost/some-path'],
        ];
    }

    /**
     * @test
     * @small
     * @dataProvider providerSendPracticeInfoRequest
     *
     * @param string   $baseUri
     * @param int|null $limit
     * @param int|null $offset
     * @param string   $expectedUri
     */
    public function shoulSendPracticeInfoRequest($baseUri, $limit, $offset, $expectedUri)
    {
        $response = new Response();

        $httpClientMock = $this->getHttpClientMock(['get']);
        $httpClientMock->expects(static::once())
            ->method('get')
            ->with($expectedUri, [])
            ->willReturn($response);

        $authenticatorMock = $this->getAuthenticatorMock($httpClientMock);

        $apiClient = new Client($httpClientMock, $authenticatorMock);
        $apiClient->setBaseUri($baseUri);

        static::assertSame($response, $apiClient->practiceInfo($limit, $offset));
    }

    /**
     * @see shoulSendPracticeInfoRequest
     *
     * @return array
     */
    public function providerSendPracticeInfoRequest()
    {
        return [
            ['http://localhost', null, null, 'http://localhost/1/practiceinfo'],
            ['http://localhost', 1, null, 'http://localhost/1/practiceinfo?limit=1'],
            ['http://localhost/', null, 2, 'http://localhost/1/practiceinfo?offset=2'],
            ['http://localhost/', 1, 2, 'http://localhost/1/practiceinfo?limit=1&offset=2'],
        ];
    }

    /**
     * @test
     * @small
     *
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Undefined API instance called: "abc".
     * @expectedExceptionCode 0
     */
    public function shouldThrowUndefinedAPIException()
    {
        $httpClientMock = $this->getHttpClientMock();
        $apiClient = new Client($httpClientMock, new VoidAuthenticator());

        /** @noinspection PhpUndefinedMethodInspection */
        $apiClient->abc();
    }

    /**
     * @test
     * @small
     *
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Practice ID is empty.
     * @expectedExceptionCode 0
     */
    public function shouldThrowEmptyPracticeIdException()
    {
        $httpClientMock = $this->getHttpClientMock();
        $apiClient = new Client($httpClientMock, new VoidAuthenticator());

        $apiClient->practice();
    }

    /**
     * @test
     * @small
     */
    public function shouldReturnPracticeApi()
    {
        $httpClientMock = $this->getHttpClientMock();
        $apiClient = new Client($httpClientMock, new VoidAuthenticator());

        static::assertInstanceOf(Practice::class, $apiClient->setPracticeId(1)->practice());
    }

    /**
     * @test
     * @small
     */
    public function shouldReturnPatientsApi()
    {
        $httpClientMock = $this->getHttpClientMock();
        $apiClient = new Client($httpClientMock, new VoidAuthenticator());

        static::assertInstanceOf(Patients::class, $apiClient->setPracticeId(1)->patients());
    }

    /**
     * @test
     * @small
     */
    public function shouldReturnAppointmentsApi()
    {
        $httpClientMock = $this->getHttpClientMock();
        $apiClient = new Client($httpClientMock, new VoidAuthenticator());

        static::assertInstanceOf(Appointments::class, $apiClient->setPracticeId(1)->appointments());
    }

    /**
     * @param HttpClient $httpClient
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|AuthenticatorInterface
     */
    protected function getAuthenticatorMock(HttpClient $httpClient)
    {
        $authenticatorMock = $this->getMockForAbstractClass(AuthenticatorInterface::class);
        $authenticatorMock->expects(static::once())
            ->method('authenticate')
            ->with($httpClient)
            ->willReturn($httpClient);

        return $authenticatorMock;
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|HttpClient
     */
    protected function getHttpClientMock(array $methods = [])
    {
        return $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }
}
