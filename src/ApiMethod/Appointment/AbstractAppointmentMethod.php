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

use TonicForHealth\AthenaHealth\ApiMethod\AbstractApiMethod;

/**
 * Class AbstractAppointmentMethod
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
abstract class AbstractAppointmentMethod extends AbstractApiMethod
{
    /**
     * @var int
     */
    protected $appointmentId;

    /**
     * {@inheritdoc}
     *
     * @return $this
     */
    public function setAppointmentId($appointmentId)
    {
        $this->appointmentId = (int) $appointmentId;

        return $this;
    }
}
