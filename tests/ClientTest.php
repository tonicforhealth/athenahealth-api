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

use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
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
     * @dataProvider providerSendGetRequest
     *
     * @param string $endpoint
     * @param array  $params
     * @param string $expectedUri
     */
    public function shouldSendGetRequest($endpoint, array $params, $expectedUri)
    {
        $response = static::$messageFactory->createResponse(200, null, [], '{}');
        $expectedHeaders = [];

        $httpClient = $this->getHttpClientMock(['get']);
        $httpClient->expects(static::once())
            ->method('get')
            ->with($expectedUri, $expectedHeaders)
            ->willReturn($response);

        $client = new Client($httpClient);

        static::assertEquals([], $client->get($endpoint, $params));
    }

    /**
     * @see shouldSendGetRequest
     *
     * @return array
     */
    public function providerSendGetRequest()
    {
        return [
            ['/endpoint', [], '/endpoint'],
            ['/endpoint?', [], '/endpoint'],
            ['/endpoint&', [], '/endpoint'],
            ['/endpoint?param=value', [], '/endpoint?param=value'],
            ['/endpoint', ['param' => 'value'], '/endpoint?param=value'],
            ['/endpoint?param1=value1', ['param2' => 'value2'], '/endpoint?param1=value1&param2=value2'],
        ];
    }

    /**
     * @test
     * @dataProvider providerSendPostRequest
     *
     * @param string $endpoint
     * @param array  $params
     * @param string $expectedUri
     * @param string $expectedBody
     */
    public function shouldSendPostRequest($endpoint, array $params, $expectedUri, $expectedBody)
    {
        $response = static::$messageFactory->createResponse(200, null, [], '{}');
        $expectedHeaders = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $httpClient = $this->getHttpClientMock(['post']);
        $httpClient->expects(static::once())
            ->method('post')
            ->with($expectedUri, $expectedHeaders, $expectedBody)
            ->willReturn($response);

        $client = new Client($httpClient);

        static::assertEquals([], $client->post($endpoint, $params));
    }

    /**
     * @see shouldSendPostRequest
     *
     * @return array
     */
    public function providerSendPostRequest()
    {
        return [
            ['/endpoint', [], '/endpoint', ''],
            ['/endpoint?param1=value1', ['param2' => 'value2'], '/endpoint?param1=value1', 'param2=value2'],
            ['/endpoint', ['param1' => 'value1', 'param2' => 'value2'], '/endpoint', 'param1=value1&param2=value2'],
        ];
    }

    /**
     * @test
     * @dataProvider providerReplacePostHeaders
     *
     * @param array $headers
     * @param array $expectedHeaders
     */
    public function shouldReplacePostHeaders(array $headers, array $expectedHeaders)
    {
        $response = static::$messageFactory->createResponse(200, null, [], '{}');

        $httpClient = $this->getHttpClientMock(['post']);
        $httpClient->expects(static::once())
            ->method('post')
            ->with('/', $expectedHeaders, '')
            ->willReturn($response);

        $client = new Client($httpClient);

        static::assertEquals([], $client->post('/', [], $headers));
    }

    /**
     * @see shouldReplacePostHeaders
     *
     * @return array
     */
    public function providerReplacePostHeaders()
    {
        return [
            [
                [],
                [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ],
            [
                [
                    'Accept' => 'application/pdf',
                ],
                [
                    'Accept' => 'application/pdf',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ],
            [
                [
                    'Content-Type' => 'application/json;charset=UTF-8',
                ],
                [
                    'Content-Type' => 'application/json;charset=UTF-8',
                ],
            ],
        ];
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|HttpClient
     */
    protected function getHttpClientMock(array $methods)
    {
        return $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }
}
