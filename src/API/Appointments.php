<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\API;

use Http\Client\Exception;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Appointments
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class Appointments extends AbstractApi
{
    /**
     * Retrieve a single appointment, given an appointment ID.
     *
     * @param int $appointmentId
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function get($appointmentId)
    {
        return $this->client->get(sprintf('/%d/appointments/%d', $this->practiceId, $appointmentId));
    }
}
