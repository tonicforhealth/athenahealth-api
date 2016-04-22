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

use TonicForHealth\AthenaHealth\Api\PracticeInterface;
use TonicForHealth\AthenaHealth\ApiMethod\Practice\DepartmentsMethod;
use TonicForHealth\AthenaHealth\ApiMethod\Practice\PingMethod;

/**
 * Class PracticeEndpoint
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class PracticeEndpoint extends AbstractApiEndpoint implements PracticeInterface
{
    /**
     * {@inheritdoc}
     */
    public function ping()
    {
        $ping = new PingMethod();
        $ping->setPracticeId($this->practiceId);

        return $this->client->sendRequest($ping);
    }

    /**
     * {@inheritdoc}
     */
    public function departments($limit = null, $offset = null)
    {
        $departments = new DepartmentsMethod();
        $departments->setPracticeId($this->practiceId);

        if (null !== $limit) {
            $departments->setLimit($limit);
        }

        if (null !== $offset) {
            $departments->setOffset($offset);
        }

        return $this->client->sendRequest($departments);
    }

    /**
     * {@inheritdoc}
     */
    public function patient($patientId)
    {
        $patient = new PatientEndpoint($this->client);
        $patient->setPracticeId($this->practiceId)
            ->setPatientId($patientId);

        return $patient;
    }

    /**
     * {@inheritdoc}
     */
    public function appointments()
    {
        $appointments = new AppointmentsEndpoint($this->client);
        $appointments->setPracticeId($this->practiceId);

        return $appointments;
    }
}
