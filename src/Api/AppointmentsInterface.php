<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Api;

use Http\Client\Exception;
use Http\Client\Plugin\Exception\ClientErrorException;
use Http\Client\Plugin\Exception\ServerErrorException;

/**
 * Interface AppointmentsInterface
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
interface AppointmentsInterface
{
    /**
     * Returns a single appointment.
     *
     * @param int $appointmentId
     *
     * @return array
     *
     * @throws ClientErrorException If response status code is a 4xx
     * @throws ServerErrorException If response status code is a 5xx
     * @throws Exception            If an error happens during processing the request
     */
    public function get($appointmentId);
}
