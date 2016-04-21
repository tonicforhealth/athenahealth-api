<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\ApiMethod\Practice;

use TonicForHealth\AthenaHealth\ApiMethod\Practice\PracticeInfoMethod;
use TonicForHealth\AthenaHealth\Tests\ApiMethod\AbstractHttpGetMethodTest;

/**
 * Class PracticeInfoMethodTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class PracticeInfoMethodTest extends AbstractHttpGetMethodTest
{
    /**
     * @test
     */
    public function shouldGetWithSpecialId()
    {
        $this->apiMethod = $this->getApiMethod();

        static::assertEquals('/1/practiceinfo', $this->apiMethod->getRequestUri());
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiMethod()
    {
        return new PracticeInfoMethod();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedRequestUri()
    {
        return '/195900/practiceinfo';
    }
}
