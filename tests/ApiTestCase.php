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
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

/**
 * Class ApiTestCase
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class ApiTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var int
     */
    protected $practiceId;

    /**
     * @var int
     */
    protected $patientId;

    /**
     * @var int
     */
    protected $appointmentId;

    /**
     * @var int
     */
    protected $requestLimit;

    /**
     * @var int
     */
    protected $requestOffset;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        // keep the ranges as non-crossing!
        $this->practiceId = mt_rand(195900, 195999);
        $this->patientId = mt_rand(1000, 9999);
        $this->appointmentId = mt_rand(650000, 659999);
        $this->requestLimit = mt_rand(1, 9);
        $this->requestOffset = mt_rand(10, 99);
    }

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
        $httpClient = $this->getHttpClientMock(['send']);
        $httpClient->expects(static::once())
            ->method('send')
            ->with($method, $uri, $headers, $body)
            ->willReturn($this->getHttpResponse());

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
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getHttpResponse()
    {
        return MessageFactoryDiscovery::find()->createResponse(200, null, [], $this->getHttpResponseBody());
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
