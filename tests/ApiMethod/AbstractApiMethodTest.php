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
use TonicForHealth\AthenaHealth\Tests\ApiTestCase;

/**
 * Class AbstractApiMethodTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
abstract class AbstractApiMethodTest extends ApiTestCase
{
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
        $this->apiMethod->setPracticeId($this->practiceId);
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
        $requestUri = $apiMethod->setLimit($this->requestLimit)->getRequestUri();
        $expectedRequestUri = sprintf('%s?limit=%d', $this->getExpectedRequestUri(), $this->requestLimit);

        static::assertEquals($expectedRequestUri, $requestUri);
    }

    /**
     * @param CollectionMethodInterface $apiMethod
     */
    protected function assertWithOffset(CollectionMethodInterface $apiMethod)
    {
        $requestUri = $apiMethod->setOffset($this->requestOffset)->getRequestUri();
        $expectedRequestUri = sprintf('%s?offset=%d', $this->getExpectedRequestUri(), $this->requestOffset);

        static::assertEquals($expectedRequestUri, $requestUri);
    }

    /**
     * @param CollectionMethodInterface $apiMethod
     */
    protected function assertWithLimitAndOffset(CollectionMethodInterface $apiMethod)
    {
        $requestUri = $apiMethod->setLimit($this->requestLimit)
            ->setOffset($this->requestOffset)
            ->getRequestUri();

        $expectedRequestUri = sprintf(
            '%s?limit=%d&offset=%d',
            $this->getExpectedRequestUri(),
            $this->requestLimit,
            $this->requestOffset
        );

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
