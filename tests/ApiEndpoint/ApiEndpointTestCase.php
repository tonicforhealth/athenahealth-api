<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\ApiEndpoint;

use Http\Discovery\MessageFactoryDiscovery;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;
use TonicForHealth\AthenaHealth\Tests\ApiTestCase;

/**
 * Class ApiEndpointTestCase
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class ApiEndpointTestCase extends ApiTestCase
{
    /**
     * @param string $method
     * @param string $uri
     * @param array  $headers
     * @param string $body
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|HttpClient
     */
    protected function getHttpClient($method, $uri, array $headers = [], $body = null)
    {
        $response = MessageFactoryDiscovery::find()->createResponse(200, null, [], $this->getHttpResponseBody());

        $httpClient = $this->getHttpClientMock(['send']);
        $httpClient->expects(static::once())
            ->method('send')
            ->with($method, $uri, $headers, $body)
            ->willReturn($response);

        return $httpClient;
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

    /**
     * @return string
     */
    protected function getHttpResponseBody()
    {
        return '{}';
    }

    /**
     * @return array
     */
    protected function getExpectedApiResponse()
    {
        return [];
    }
}
