<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\ApiMethod;

use TonicForHealth\AthenaHealth\ApiMethod\ApiMethodInterface;
use TonicForHealth\AthenaHealth\ApiMethod\CollectionMethodInterface;

/**
 * Class AbstractApiMethodTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
abstract class AbstractApiMethodTest extends \PHPUnit_Framework_TestCase
{
    const FIXTURES_PRACTICE_ID = 195900;
    const FIXTURES_PATIENT_ID = 1234;
    const FIXTURES_APPOINTMENT_ID = 654321;

    /**
     * @var ApiMethodInterface
     */
    protected $apiMethod;

    /**
     * @test
     */
    public function shouldGet()
    {
        static::assertEquals($this->getExpectedRequestUri(), $this->apiMethod->getRequestUri());

        if ($this->apiMethod instanceof CollectionMethodInterface) {
            $this->assertWithLimit(clone $this->apiMethod);
            $this->assertWithOffset(clone $this->apiMethod);
            $this->assertWithLimitAndOffset(clone $this->apiMethod);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->apiMethod = $this->getApiMethod();
        $this->apiMethod->setPracticeId(static::FIXTURES_PRACTICE_ID);
    }

    /**
     * {@inheritdoc}
     */
    protected function assertPreConditions()
    {
        parent::assertPreConditions();

        static::assertInstanceOf(ApiMethodInterface::class, $this->apiMethod);
    }

    /**
     * @param CollectionMethodInterface $apiMethod
     */
    protected function assertWithLimit(CollectionMethodInterface $apiMethod)
    {
        $limit = mt_rand(1, 100);

        $requestUri = $apiMethod->setLimit($limit)->getRequestUri();
        $expectedRequestUri = sprintf('%s?limit=%d', $this->getExpectedRequestUri(), $limit);

        static::assertEquals($expectedRequestUri, $requestUri);
    }

    /**
     * @param CollectionMethodInterface $apiMethod
     */
    protected function assertWithOffset(CollectionMethodInterface $apiMethod)
    {
        $offset = mt_rand(1, 100);

        $requestUri = $apiMethod->setOffset($offset)->getRequestUri();
        $expectedRequestUri = sprintf('%s?offset=%d', $this->getExpectedRequestUri(), $offset);

        static::assertEquals($expectedRequestUri, $requestUri);
    }

    /**
     * @param CollectionMethodInterface $apiMethod
     */
    protected function assertWithLimitAndOffset(CollectionMethodInterface $apiMethod)
    {
        $limit = mt_rand(1, 10);
        $offset = mt_rand(11, 100);

        $requestUri = $apiMethod->setLimit($limit)->setOffset($offset)->getRequestUri();
        $expectedRequestUri = sprintf('%s?limit=%d&offset=%d', $this->getExpectedRequestUri(), $limit, $offset);

        static::assertEquals($expectedRequestUri, $requestUri);
    }

    /**
     * @return ApiMethodInterface
     */
    abstract protected function getApiMethod();

    /**
     * @return string
     */
    abstract protected function getExpectedRequestUri();
}
