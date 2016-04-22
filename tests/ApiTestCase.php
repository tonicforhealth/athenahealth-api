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
}
