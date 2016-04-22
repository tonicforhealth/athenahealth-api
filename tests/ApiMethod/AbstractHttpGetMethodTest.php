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
 * Class AbstractHttpGetMethodTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
abstract class AbstractHttpGetMethodTest extends AbstractApiMethodTest
{
    /**
     * {@inheritdoc}
     */
    protected function assertPreConditions()
    {
        parent::assertPreConditions();

        static::assertEquals('GET', $this->apiMethod->getRequestMethod());
        static::assertEquals([], $this->apiMethod->getRequestHeaders());
        static::assertNull($this->apiMethod->getRequestBody());
    }
}
