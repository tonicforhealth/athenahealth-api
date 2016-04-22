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

use TonicForHealth\AthenaHealth\Api\PracticeInterface;
use TonicForHealth\AthenaHealth\ApiEndpoint\PracticeEndpoint;
use TonicForHealth\AthenaHealth\ApiMethod\ApiMethodInterface;
use TonicForHealth\AthenaHealth\ApiMethod\Practice\PracticeInfoMethod;
use TonicForHealth\AthenaHealth\Client;

/**
 * Class ClientTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class ClientTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldGetPractice()
    {
        $client = new Client($this->getHttpClientMock([]));

        /** @var PracticeInterface|PracticeEndpoint $practice */
        $practice = $client->practice($this->practiceId);

        static::assertInstanceOf(PracticeInterface::class, $practice);
        static::assertInstanceOf(PracticeEndpoint::class, $practice);
        static::assertEquals($this->practiceId, $practice->getPracticeId());
    }

    /**
     * @test
     */
    public function shouldSendRequest()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|ApiMethodInterface $apiMethod */
        $apiMethod = $this->getMockForAbstractClass(ApiMethodInterface::class);

        $apiMethod->expects(static::once())
            ->method('getRequestMethod')
            ->willReturn('RequestMethod');

        $apiMethod->expects(static::once())
            ->method('getRequestUri')
            ->willReturn('RequestUri');

        $apiMethod->expects(static::once())
            ->method('getRequestHeaders')
            ->willReturn(['RequestHeaders']);

        $apiMethod->expects(static::once())
            ->method('getRequestBody')
            ->willReturn('RequestBody');

        $httpClient = $this->getHttpClient('RequestMethod', 'RequestUri', ['RequestHeaders'], 'RequestBody');
        $client = new Client($httpClient);

        static::assertEquals($this->getExpectedApiResponse(), $client->sendRequest($apiMethod));
    }

    /**
     * @test
     */
    public function shouldGetPracticeInfo()
    {
        $practiceInfo = new PracticeInfoMethod();

        $this->assertPracticeInfo($practiceInfo, null, null);
    }

    /**
     * @test
     */
    public function shouldGetPracticeInfoWithLimit()
    {
        $practiceInfo = new PracticeInfoMethod();
        $practiceInfo->setLimit($this->requestLimit);

        $this->assertPracticeInfo($practiceInfo, $this->requestLimit, null);
    }

    /**
     * @test
     */
    public function shouldGetPracticeInfoWithOffset()
    {
        $practiceInfo = new PracticeInfoMethod();
        $practiceInfo->setOffset($this->requestOffset);

        $this->assertPracticeInfo($practiceInfo, null, $this->requestOffset);
    }

    /**
     * @test
     */
    public function shouldGetPracticeInfoWithLimitAndOffset()
    {
        $practiceInfo = new PracticeInfoMethod();
        $practiceInfo->setLimit($this->requestLimit)->setOffset($this->requestOffset);

        $this->assertPracticeInfo($practiceInfo, $this->requestLimit, $this->requestOffset);
    }

    /**
     * @param PracticeInfoMethod $practiceInfo
     * @param int|null           $limit
     * @param int|null           $offset
     */
    protected function assertPracticeInfo(PracticeInfoMethod $practiceInfo, $limit, $offset)
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Client $client */
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['sendRequest'])
            ->getMock();

        $client->expects(static::once())
            ->method('sendRequest')
            ->with($practiceInfo)
            ->willReturn([]);

        static::assertEquals([], $client->practiceInfo($limit, $offset));
    }
}
