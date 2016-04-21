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

use TonicForHealth\AthenaHealth\ApiMethod\Practice\Departments;
use TonicForHealth\AthenaHealth\Tests\ApiMethod\AbstractHttpGetMethodTest;

/**
 * Class DepartmentsTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class DepartmentsTest extends AbstractHttpGetMethodTest
{
    /**
     * {@inheritdoc}
     */
    protected function getApiMethod()
    {
        return new Departments();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedRequestUri()
    {
        return '/195900/departments';
    }
}
