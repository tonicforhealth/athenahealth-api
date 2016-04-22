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

use TonicForHealth\AthenaHealth\ApiMethod\Appointment\AppointmentMethod;
use TonicForHealth\AthenaHealth\Tests\ApiMethod\AbstractHttpGetMethodTest;

/**
 * Class AppointmentMethodTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class AppointmentMethodTest extends AbstractHttpGetMethodTest
{
    /**
     * {@inheritdoc}
     */
    protected function getApiMethod()
    {
        $appointment = new AppointmentMethod();
        $appointment->setAppointmentId($this->appointmentId);

        return $appointment;
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedRequestUri()
    {
        return sprintf('/%d/appointments/%d', $this->practiceId, $this->appointmentId);
    }
}
