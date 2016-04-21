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
 * Interface PracticeInterface
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
interface PracticeInterface
{
    /**
     * Returns an acknowledgement that an API key has access to the given practice.
     *
     * @return array
     *
     * @throws ClientErrorException If response status code is a 4xx
     * @throws ServerErrorException If response status code is a 5xx
     * @throws Exception            If an error happens during processing the request
     */
    public function ping();

    /**
     * Returns a list of departments.
     *
     * @param int|null $limit  (Optional) Number of entries to return (default 1500, max 5000)
     * @param int|null $offset (Optional) Starting point of entries; 0-indexed
     *
     * @return array
     *
     * @throws ClientErrorException If response status code is a 4xx
     * @throws ServerErrorException If response status code is a 5xx
     * @throws Exception            If an error happens during processing the request
     */
    public function departments($limit = null, $offset = null);

    /**
     * Returns a patient endpoint.
     *
     * @param int $patientId
     *
     * @return PatientInterface
     */
    public function patient($patientId);

    /**
     * Returns appointments endpoint.
     *
     * @return AppointmentsInterface
     */
    public function appointments();
}
