<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\ApiMethod\Appointment;

use TonicForHealth\AthenaHealth\ApiMethod\Appointment\Appointment;
use TonicForHealth\AthenaHealth\Tests\ApiMethod\AbstractHttpGetMethodTest;

/**
 * Class AppointmentTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class AppointmentTest extends AbstractHttpGetMethodTest
{
    /**
     * @var int
     */
    protected $appointmentId;

    /**
     * {@inheritdoc}
     */
    protected function getApiMethod()
    {
        $appointment = new Appointment();
        $appointment->setAppointmentId(static::FIXTURES_APPOINTMENT_ID);

        return $appointment;
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedRequestUri()
    {
        return '/195900/appointments/654321';
    }
}
