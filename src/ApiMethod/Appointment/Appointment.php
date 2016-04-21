<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\ApiMethod\Appointment;

use TonicForHealth\AthenaHealth\ApiMethod\HttpGetMethodTrait;

/**
 * Class Appointment
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class Appointment extends AbstractAppointmentMethod
{
    use HttpGetMethodTrait;

    /**
     * {@inheritdoc}
     */
    public function getRequestUri()
    {
        return sprintf('/%d/appointments/%d', $this->practiceId, $this->appointmentId);
    }
}
