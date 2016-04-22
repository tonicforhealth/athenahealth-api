<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\ApiEndpoint;

use TonicForHealth\AthenaHealth\Api\AppointmentsInterface;
use TonicForHealth\AthenaHealth\ApiMethod\Appointment\AppointmentMethod;

/**
 * Class AppointmentsEndpoint
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class AppointmentsEndpoint extends AbstractApiEndpoint implements AppointmentsInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($appointmentId)
    {
        $appointment = new AppointmentMethod();
        $appointment->setPracticeId($this->practiceId)
            ->setAppointmentId($appointmentId);

        return $this->client->sendRequest($appointment);
    }
}
