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

/**
 * Class AbstractHttpPostMethodTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
abstract class AbstractHttpPostMethodTest extends AbstractApiMethodTest
{
    /**
     * {@inheritdoc}
     */
    protected function assertPreConditions()
    {
        parent::assertPreConditions();

        static::assertEquals('POST', $this->apiMethod->getRequestMethod());
        static::assertEquals($this->getExpectedRequestHeaders(), $this->apiMethod->getRequestHeaders());
        static::assertEquals($this->getExpectedRequestBody(), $this->apiMethod->getRequestBody());
    }

    /**
     * @return array
     */
    protected function getExpectedRequestHeaders()
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    /**
     * @return string
     */
    abstract protected function getExpectedRequestBody();
}
